<?php

namespace MashinaMashina\Bxmod\Admin\Form\Fillers\Relations;

class Reference extends Relation
{
	public static function fillEntity($entity, $field, $value)
	{
		$object = null;
		if ($value !== '')
		{
			$object = ($field->getRefEntityName() . 'Table')::wakeUpObject($value);
		}
		
		$entity->set($field->getName(), $object);
	}
}

