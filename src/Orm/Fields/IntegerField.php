<?php

namespace MashinaMashina\Bxmod\Orm\Fields;

use \Bitrix\Main\Orm\Fields;

class IntegerField extends Fields\IntegerField
{
	use ParametersTrait;
	
	public function getDataType()
	{
		return 'integer';
	}
}