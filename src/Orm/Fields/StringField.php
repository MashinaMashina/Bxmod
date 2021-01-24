<?php

namespace MashinaMashina\Bxmod\Orm\Fields;

use \Bitrix\Main\Orm\Fields;

class StringField extends Fields\StringField
{
	use ParametersTrait, FieldTrait;
	
	public function getDataType()
	{
		return 'string';
	}
}