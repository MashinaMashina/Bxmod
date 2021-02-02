<?php

namespace MashinaMashina\Bxmod\Admin\Form\Fillers;

use \Bitrix\Main\ORM\Objectify\EntityObject;
use \Bitrix\Main\ORM\Fields;
use \Bitrix\Main\HttpRequest;

abstract class Field
{
	public static function getValueFromRequest(HttpRequest $request, Fields\Field $field)
	{
		return $request->getPost($field->getName());
	}
	
	public static function fillEntity(EntityObject $entity, Fields\Field $field, $value)
	{
		$entity->set($field->getName(), $value);
	}
}