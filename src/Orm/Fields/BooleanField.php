<?php

namespace MashinaMashina\Bxmod\Orm\Fields;

use \Bitrix\Main\Orm\Fields;

class BooleanField extends Fields\BooleanField
{
	use ParametersTrait, FieldTrait;
	
	public function getDataType()
	{
		return 'boolean';
	}
}
