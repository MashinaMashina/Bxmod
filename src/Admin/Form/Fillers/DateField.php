<?php

namespace MashinaMashina\Bxmod\Admin\Form\Fillers;

use \Bitrix\Main\Type\Date;

class DateField extends ScalarField
{
	public static function fillEntity($entity, $field, $value)
	{
		if (! is_object($value) and ! empty($value))
		{
			$value = Date::createFromTimestamp(strtotime($value));
		}
		
		return parent::fillEntity($entity, $field, $value);
	}
}