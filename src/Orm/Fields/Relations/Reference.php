<?php
namespace MashinaMashina\Bxmod\ORM\Fields\Relations;

use \Bitrix\Main\ORM\Fields\Relations;
use \MashinaMashina\Bxmod\ORM\Fields\ParametersTrait;

class Reference extends Relations\Reference
{
	use ParametersTrait, RelationTrait;
}