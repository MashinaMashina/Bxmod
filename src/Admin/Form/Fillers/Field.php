<?php

namespace MashinaMashina\Bxmod\Admin\Form\Fillers;

use \Bitrix\Main\ORM\Objectify\EntityObject;
use \Bitrix\Main\ORM\Fields;

abstract class Field
{
	public static function fillEntity(EntityObject $entity, Fields\Field $field, $value)
	{
		$entity->set($field->getName(), $value);
	}
}