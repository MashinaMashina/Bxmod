<?php

namespace MashinaMashina\Bxmod\Orm\Fields;

use \Bitrix\Main\Orm\Fields;

class DatetimeField extends Fields\DatetimeField
{
	use ParametersTrait, FieldTrait;
	
	public function getDataType()
	{
		return 'datetime';
	}
}