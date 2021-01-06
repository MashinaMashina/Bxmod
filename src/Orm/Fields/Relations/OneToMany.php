<?php
namespace MashinaMashina\Bxmod\ORM\Fields\Relations;

use \Bitrix\Main\ORM\Fields\Relations;
use \MashinaMashina\Bxmod\ORM\Fields\ParametersTrait;

class OneToMany extends Relations\OneToMany
{
	use ParametersTrait, RelationTrait;
}