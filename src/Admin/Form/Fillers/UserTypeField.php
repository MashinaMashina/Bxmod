<?php

namespace MashinaMashina\Bxmod\Admin\Form\Fillers;

use \Bitrix\Main\ORM\Objectify\EntityObject;
use \Bitrix\Main\ORM\Fields;
use \Bitrix\Main\HttpRequest;
use \MashinaMashina\Bxmod\Tools\UserField;

class UserTypeField extends Field
{
	public static function getValueFromRequest(HttpRequest $request, Fields\Field $field)
	{
		if (empty($field->getParameter('bxmod_uf_type')))
		{
			UserField::fillUfFieldInfo($field);
		}
		
		/*
		 * Передаем в обработчик соответствующий типу пользовательского поля
		 */
		
		return (__NAMESPACE__ .'\\'. $field->getParameter('bxmod_uf_type'))::getValueFromRequest($request, $field);
	}
	
	public static function fillEntity(EntityObject $entity, Fields\Field $field, $value)
	{
		if (empty($field->getParameter('bxmod_uf_type')))
		{
			UserField::fillUfFieldInfo($field);
		}
		
		/*
		 * Передаем в обработчик соответствующий типу пользовательского поля
		 */
		
		return (__NAMESPACE__ .'\\'. $field->getParameter('bxmod_uf_type'))::fillEntity($entity, $field, $value);
	}
}
