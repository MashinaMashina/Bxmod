<?php

namespace MashinaMashina\Bxmod\Admin\Form\Fillers;

use \Bitrix\Main\ORM\Objectify\EntityObject;
use \Bitrix\Main\ORM\Fields;
use \MashinaMashina\Bxmod\Tools\UserField;

class UserTypeField extends Field
{
	public static function fillEntity(EntityObject $entity, Fields\Field $field, $value)
	{
		if (empty($field->getParameter('bxmod_type')))
		{
			UserField::fillUfFieldInfo($field);
		}
		
		if ($field->isMultiple())
		{
			/*
			 * Множественное поле пользователю выводится как текст. Новая строка - отдельно значение
			 */
			$value = explode("\r\n", $value);
		}
		
		/*
		 * Передаем в обработчик соответствующий типу пользовательского поля
		 */
		return (__NAMESPACE__ .'\\'. $field->getParameter('bxmod_type'))::fillEntity($entity, $field, $value);
	}
}
