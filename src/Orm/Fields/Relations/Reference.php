<?php
namespace MashinaMashina\Bxmod\Orm\Fields\Relations;

use \Bitrix\Main\Orm\Fields\Relations;
use \MashinaMashina\Bxmod\Orm\Fields\ParametersTrait;

class Reference extends Relations\Reference
{
	use ParametersTrait, RelationTrait;
}