<?php

namespace MashinaMashina\Bxmod\Admin\Form\Fillers;

abstract class Field
{
	public static function fillEntity($entity, $field, $value)
	{
		$entity->set($field->getName(), $value);
	}
}