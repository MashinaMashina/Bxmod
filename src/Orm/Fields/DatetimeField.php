<?php

namespace MashinaMashina\Bxmod\Orm\Fields;

use \Bitrix\Main\Orm\Fields;

class DatetimeField extends Fields\DatetimeField
{
	use ParametersTrait;
	
	public function getDataType()
	{
		return 'datetime';
	}
}