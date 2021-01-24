<?php
namespace MashinaMashina\Bxmod\Orm\Fields\Relations;

use \Bitrix\Main\Orm\Fields\Relations;
use \MashinaMashina\Bxmod\Orm\Fields\ParametersTrait;
use \MashinaMashina\Bxmod\Orm\Fields\FieldTrait;

class OneToMany extends Relations\OneToMany
{
	use ParametersTrait, FieldTrait, RelationTrait;
}