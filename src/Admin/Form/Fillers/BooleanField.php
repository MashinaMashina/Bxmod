<?php

namespace MashinaMashina\Bxmod\Admin\Form\Fillers;

class BooleanField extends ScalarField
{
	public static function fillEntity($entity, $field, $value)
	{
		if (! is_bool($value))
		{
			if (is_array($value))
			{
				foreach ($value as &$val)
				{
					if (! is_bool($val))
					{
						$val = ($val === 'Y' ? true : false);
					}
				}
			}
			else
			{
				$value = ($value === 'Y' ? true : false);
			}
		}
		
		return parent::fillEntity($entity, $field, $value);
	}
}
