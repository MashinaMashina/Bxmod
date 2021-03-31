<?php

namespace MashinaMashina\Bxmod\Admin\Form\Editors;

use \MashinaMashina\Bxmod\Tools\Html;
use \Bitrix\Main\ORM\Objectify\EntityObject;
use \Bitrix\Main\ORM\Fields;

class TextField extends StringField
{
	public static function buildInput(Fields\Field $field, EntityObject $entity, $table, $tagData = [])
	{
		$value = $entity->get($field->getName());
		/*
		 * Множественные пользовательские поля передаются как массив
		 */
		if (is_array($value))
		{
			$value = implode("\r\n", $value);
		}
		
		if ($field->getParameter('bxmod_readonly') === true)
		{
			return htmlspecialcharsbx($value);
		}
		else
		{
			return Html::buildTag('textarea', $tagData + [
				'name' => $field->getName(),
			], htmlentities($value));
		}
	}
}