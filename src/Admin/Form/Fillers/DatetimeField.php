<?php

namespace MashinaMashina\Bxmod\Admin\Form\Fillers;

use \Bitrix\Main\Type\DateTime;

class DatetimeField extends DateField
{
	public static function fillEntity($entity, $field, $value)
	{
		if (! is_object($value) and ! empty($value))
		{
			if (is_array($value))
			{
				foreach ($value as &$val)
				{
					$val = DateTime::createFromTimestamp(strtotime($val));
				}
			}
			else
			{
				$value = DateTime::createFromTimestamp(strtotime($value));
			}
		}
		
		return parent::fillEntity($entity, $field, $value);
	}
}