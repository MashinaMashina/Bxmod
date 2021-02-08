<?php

namespace MashinaMashina\Bxmod\Admin\Lists\Viewers;

use \Bitrix\Main\ORM\Entity;
use \Bitrix\Main\ORM\Objectify\EntityObject;
use \Bitrix\Main\Localization\Loc;

class BooleanField extends ScalarField
{
	public static function prepareView(Entity $entityTable, EntityObject $entity, $value)
	{
		return Loc::getMessage($value ? 'bxmod_yes' : 'bxmod_no');
	}
}
