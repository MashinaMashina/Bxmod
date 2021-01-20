<?php
namespace MashinaMashina\Bxmod\Orm\Fields\Relations;

use \Bitrix\Main\Orm\Fields\Relations;
use \MashinaMashina\Bxmod\Orm\Fields\ParametersTrait;

class OneToMany extends Relations\OneToMany
{
	use ParametersTrait, RelationTrait;
}