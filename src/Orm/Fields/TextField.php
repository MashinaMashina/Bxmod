<?php

namespace MashinaMashina\Bxmod\Orm\Fields;

use \Bitrix\Main\Orm\Fields;

class TextField extends Fields\TextField
{
	use ParametersTrait, FieldTrait;
	
	public function getDataType()
	{
		return 'text';
	}
}