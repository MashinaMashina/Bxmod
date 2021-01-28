<?php

namespace MashinaMashina\Bxmod\Orm\Fields;

use \Bitrix\Main\Orm\Fields;

class EnumField extends Fields\EnumField
{
	use ParametersTrait, FieldTrait;
	
	public function getDataType()
	{
		return 'enum';
	}
}