<?php

namespace MashinaMashina\Bxmod\Admin\Form\Fillers\Relations;

use \MashinaMashina\Bxmod\Admin\Form\Fillers;

class OneToMany extends Relation
{
	public static function fillEntity($entity, $field, $value)
	{
		if (! is_array($value))
			$value = [$value];
		
		$collection = [];
		if (reset($entity->primary))
		{
			$collection = $entity->get($name);
		}
		
		static::fillEditorType($entity, $field, $value, $collection);
		
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
				$entity->addTo($name, ($field->getRefEntityName() . 'Table')::wakeUpObject($primary));
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
				$entity->removeFrom($name, $collectionEntity);
			}
		}
		
		$remoteFieldName = $field->getRefField()->getName();
		$refEntities = Fillers\Iterator::fillEntity($field, $value, [
			$name => $entity,
		]);
		
		// $this->tieEntities($refEntities, $remoteFieldName, $entity);
	}
	
	protected function fillRelationEntities($field, $values, $parentEntity)
	{
		$refTable = $field->getRefEntityName() . 'Table';
		
		$result = [];
		foreach ($values as $value)
		{
			if ($value['_primary'] > 0)
			{
				$refEntity = ($refTable)::wakeUpObject($value['_primary']);
			}
			else
			{
				$refEntity = ($refTable)::createObject();
			}
			
			$this->fillSavingEntity(($refTable)::getEntity(), $refEntity, $value);
			
			$result[] = $refEntity;
		}
		
		return $result;
	}
}
