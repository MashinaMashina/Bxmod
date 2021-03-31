<?php

namespace MashinaMashina\Bxmod\Admin\Form\Fillers;

use \Bitrix\Main\ORM\Entity;
use \Bitrix\Main\ORM\Objectify\EntityObject;
use \Bitrix\Main\HttpRequest;

class Iterator
{
	public static function fillEntity(Entity $entityTable, EntityObject $entity, HttpRequest $request)
	{
		static::fixReferences($entity);
		
		$avaibledFields = $entityTable->getFields();
		foreach ($avaibledFields as $field)
		{
			if (isset($field->isbxmod) and $field->isbxmod)
			{
				$filler = $field->getFillerClass();
			}
			else
			{
				$filler = str_replace('Bitrix\Main\ORM\Fields', 'MashinaMashina\Bxmod\Admin\Form\Fillers', get_class($field));
			}
			
			$name = $field->getName();
			$value = ($filler)::getValueFromRequest($request, $field);
			
			$editable = ($field->getParameter('bxmod_readonly') !== true and $field->getParameter('bxmod_hidden') !== true);
			
			if (! $editable or $value === null)
				continue;
			
			($filler)::fillEntity($entity, $field, $value);
		}
	}
	
	/*
	 * Фикс для версий Битрикс ниже 20.1.
	 * Исправление ошибки
	 * Call to a member function save() on null (0)
	 * /bitrix/modules/main/lib/orm/objectify/entityobject.php:1757
	 * Удаляем пустые референсы из актуальных значений
	 */
	public static function fixReferences(EntityObject $entity)
	{
		$currentValues = $entity->collectValues(\Bitrix\Main\ORM\Objectify\Values::CURRENT, \Bitrix\Main\ORM\Fields\FieldTypeMask::REFERENCE);
		$actualValues = $entity->collectValues(\Bitrix\Main\ORM\Objectify\Values::ACTUAL, \Bitrix\Main\ORM\Fields\FieldTypeMask::REFERENCE);
		
		foreach ($actualValues as $fieldName => $value)
		{
			if (is_null($value))
			{
				$entity->sysUnset($fieldName);
				
				if (isset($currentValues[$fieldName]))
				{
					$entity->sysSetValue($fieldName, $$currentValues[$fieldName]);
				}
			}
		}
	}
}