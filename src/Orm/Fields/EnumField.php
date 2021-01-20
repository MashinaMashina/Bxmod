<?php

namespace MashinaMashina\Bxmod\Orm\Fields;

use \Bitrix\Main\Orm\Fields\EnumField;

class EnumField extends Fields\EnumField
{
	use ParametersTrait;
	
	public function getDataType()
	{
		return 'enum';
	}
}