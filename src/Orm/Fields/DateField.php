<?php

namespace MashinaMashina\Bxmod\Orm\Fields;

use \Bitrix\Main\Orm\Fields;

class DateField extends Fields\DateField
{
	use ParametersTrait;
	
	public function getDataType()
	{
		return 'date';
	}
}