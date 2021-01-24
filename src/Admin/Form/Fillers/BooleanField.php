<?php

namespace MashinaMashina\Bxmod\Admin\Form\Fillers;

class BooleanField extends ScalarField
{
	public static function fillEntity($entity, $field, $value)
	{
		if (! is_bool($value))
		{
			$value = ($value === 'Y' ? true : false);
		}
		
		return parent::fillEntity($entity, $field, $value);
	}
}
