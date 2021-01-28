<?php

namespace MashinaMashina\Bxmod\Admin\Form\Fillers\Relations;

use \MashinaMashina\Bxmod\Admin\Form\Fillers;

class OneToMany extends Relation
{
	protected static $lazySavingEntities = [];
	
	public static function fillEntity($entity, $field, $value)
	{
		if (! is_array($value))
			$value = [$value];
		
		$collection = [];
		if (reset($entity->primary))
		{
			$collection = $entity->get($field->getName());
		}
		
		if ($field->getParameter('bxmod_relation_view_type') === 'editor')
		{
			return static::fillEditorType($entity, $field, $value, $collection);
		}
		
		$primaries = [];
		foreach ($collection as $collectionEntity)
		{
			$primary = reset($collectionEntity->primary);
			$primaries[] = $primary;
			if (! in_array($primary, $value))
			{
				$collection->remove($collectionEntity);
			}
		}
		
		foreach ($value as $primary)
		{
			if (! in_array($primary, $primaries))
			{
				$entity->addTo($field->getName(), ($field->getRefEntityName() . 'Table')::wakeUpObject($primary));
			}
		}
	}
	
	protected static function fillEditorType($entity, $field, $value, $collection)
	{
		$primaries = array_column($value, '_primary');
		foreach ($collection as $collectionEntity)
		{
			$primary = reset($collectionEntity->primary);
			if (! in_array($primary, $primaries))
			{
				$entity->removeFrom($field->getName(), $collectionEntity);
			}
		}
		
		$remoteFieldName = $field->getRefField()->getName();
		$refEntities = static::fillRelationEntities($field, $value, [
			$field->getName() => $entity,
		]);
		
		static::saveReferences($refEntities, $remoteFieldName, $entity);
	}
	
	protected static function saveReferences($refEntities, $remoteFieldName, $entity)
	{
		if (! is_array($refEntities))
			$refEntities = [$refEntities];
		
		foreach ($refEntities as $refEntity)
		{
			static::$lazySavingEntities[] = [
				'target' => $refEntity,
				'fieldName' => $remoteFieldName,
				'entity' => $entity,
			];
		}
		
		$primary = reset($entity->primary);
		
		/*
		 * Если сущность еще не имеет ID, то к ней
		 * нельзя привязывать посторонние сущности.
		 * Дожидаемся сохранения
		 */
		if (! empty($primary))
		{
			static::lazySaveReferences();
		}
		else
		{
			static::registerSaveCallback($entity, 'onAfterAdd', get_called_class(), 'lazySaveReferences');
		}
	}
	
	protected static function registerSaveCallback($entity, $event, $toClass, $toFunction)
	{
		static $hasEvent;
		
		if ($hasEvent)
		{
			return;
		}
		
		$hasEvent = true;
		
		$sysEntity = $entity->sysGetEntity();
		$class = $sysEntity->getFullName();
		$module = $sysEntity->getModule();
		
		$eventManager = \Bitrix\Main\EventManager::getInstance();
		$eventManager->addEventHandler($module, $class . '::' . $event, [$toClass, $toFunction]);
	}
	
	public static function lazySaveReferences()
	{
		foreach (static::$lazySavingEntities as $key => $data)
		{
			$primary = reset($data['entity']->primary);
			if (! empty($primary))
			{
				$data['target']->set($data['fieldName'], $data['entity']);
				$data['target']->save();
				
				unset(static::$lazySavingEntities[$key]);
			}
		}
	}
	
	protected function fillRelationEntities($field, $values, $parentEntity)
	{
		$refTable = $field->getRefEntityName() . 'Table';
		
		$result = [];
		foreach ($values as $value)
		{
			if ($value['_primary'] === 'none')
			{
				continue;
			}
			
			if ($value['_primary'] > 0)
			{
				$refEntity = ($refTable)::wakeUpObject($value['_primary']);
			}
			else
			{
				$refEntity = ($refTable)::createObject();
			}
			
			Fillers\Iterator::fillEntity(($refTable)::getEntity(), $refEntity, $value);
			
			$result[] = $refEntity;
		}
		
		return $result;
	}
}
