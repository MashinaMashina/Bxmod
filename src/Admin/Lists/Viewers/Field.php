<?php

namespace MashinaMashina\Bxmod\Admin\Lists\Viewers;

use \Bitrix\Main\ORM\Entity;
use \Bitrix\Main\ORM\Objectify\EntityObject;

abstract class Field
{
	public static function prepareView(Entity $entityTable, EntityObject $entity, $value)
	{
		return $value;
	}
}