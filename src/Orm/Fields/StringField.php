<?php

namespace MashinaMashina\Bxmod\Orm\Fields;

use \Bitrix\Main\Orm\Fields;

class StringField extends Fields\StringField
{
	use ParametersTrait;
	
	public function getDataType()
	{
		return 'string';
	}
}