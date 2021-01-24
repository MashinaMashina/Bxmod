<?php

namespace MashinaMashina\Bxmod\Admin\Form\Editors;

class Iterator
{
	public static function fillEntity($entityTable, $entity, array $data)
	{
		var_dump(get_class($entityTable), get_class($entity));
		exit;
		$avaibledFields = $entityTable->getFields();
		foreach ($avaibledFields as $field)
		{
			$name = $field->getName();
			$value = isset($data[$name]) ? $data[$name] : null;
			$editable = ($field->getParameter('bxmod_readonly') !== true and $field->getParameter('bxmod_hidden') !== true);
			
			if (! $editable or $value === null)
				continue;
			
			($field->getFillerClass())::fillEntity($entity, $field, $value);
			
			/*
			switch ($field->getTypeMask())
			{
				case FieldTypeMask::SCALAR:
					switch ($field->getDataType())
					{
						case 'boolean':
							$value = ($value === 'Y' ? true : false);
							break;
						
						case 'date':
							$value = Date::createFromTimestamp(strtotime($value));
							break;
					}
					$entity->set($field->getName(), $value);
					break;
				
				case FieldTypeMask::REFERENCE:
					$entity->set($name, ($field->getRefEntityName() . 'Table')::wakeUpObject($value));
					break;
				
				case FieldTypeMask::ONE_TO_MANY:
				case FieldTypeMask::MANY_TO_MANY:
					if (! is_array($value))
						$value = [$value];
					
					$collection = [];
					if (reset($entity->primary))
					{
						$collection = $entity->get($name);
					}
					
					if ($field->getParameter('bxmod_relation_view_type') === 'editor')
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
						$refEntities = $this->fillRelationEntities($field, $value, [
							$name => $entity,
						]);
						
						$this->tieEntities($refEntities, $remoteFieldName, $entity);
						
						continue;
					}
					else
					{
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
					
					break;
				
				default:
					throw new \Exception('Unsupported field data mask ' . $field->getTypeMask());
					break;
			}
			*/
		}
	}
}