<?php

namespace MashinaMashina\Bxmod\Admin\Form\Fillers\Relations;

class Reference extends Relation
{
	public static function fillEntity($entity, $field, $value)
	{
		$entity->set($field->getName(), ($field->getRefEntityName() . 'Table')::wakeUpObject($value));
	}
}

