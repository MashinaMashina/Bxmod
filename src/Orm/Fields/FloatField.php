<?php

namespace MashinaMashina\Bxmod\Orm\Fields;

use \Bitrix\Main\Orm\Fields;

class FloatField extends Fields\FloatField
{
	use ParametersTrait;
	
	public function getDataType()
	{
		return 'float';
	}
}