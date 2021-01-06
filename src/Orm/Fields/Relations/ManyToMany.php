<?php
namespace MashinaMashina\Bxmod\ORM\Fields\Relations;

use \Bitrix\Main\ORM\Fields\Relations;
use \MashinaMashina\Bxmod\ORM\Fields\ParametersTrait;

class ManyToMany extends Relations\ManyToMany
{
	use ParametersTrait, RelationTrait;
}