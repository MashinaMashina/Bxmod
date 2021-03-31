<?php

namespace MashinaMashina\Bxmod\Admin\Form\Editors;

use \Bitrix\Main\ORM\Objectify\EntityObject;
use \Bitrix\Main\ORM\Fields;
use \MashinaMashina\Bxmod\Tools\UserField;

class UserTypeField extends Field
{
	public static function buildInput(Fields\Field $field, EntityObject $entity, $table, $tagData = [])
	{
		if (empty($field->getParameter('bxmod_type')))
		{
			UserField::fillUfFieldInfo($field);
		}
		
		/*
		 * Передаем в обработчик соответствующий типу пользовательского поля
		 */
		return (__NAMESPACE__ .'\\'. $field->getParameter('bxmod_type'))::buildInput($field, $entity, $table, $tagData);
	}
}
