<?php

namespace MashinaMashina\Bxmod\Admin\Form\Editors;

use \MashinaMashina\Bxmod\Tools\Html;
use \Bitrix\Main\ORM\Objectify\EntityObject;
use \Bitrix\Main\ORM\Fields;

class TextField extends StringField
{
	public static function buildInput(Fields\Field $field, EntityObject $entity, $table, $tagData = [])
	{
		if ($field->getParameter('bxmod_readonly') === true)
		{
			return htmlspecialcharsbx($entity[$field->getName()]);
		}
		else
		{
			return Html::buildTag('textarea', $tagData + [
				'name' => $field->getName(),
			], htmlentities($entity->get($field->getName())));
		}
	}
}