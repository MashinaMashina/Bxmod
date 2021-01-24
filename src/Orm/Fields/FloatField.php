<?php

namespace MashinaMashina\Bxmod\Orm\Fields;

use \Bitrix\Main\Orm\Fields;

class FloatField extends Fields\FloatField
{
	use ParametersTrait, FieldTrait;
	
	public function getDataType()
	{
		return 'float';
	}
}