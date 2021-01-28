<?php

namespace MashinaMashina\Bxmod\Admin\Form\Editors;

use \MashinaMashina\Bxmod\Tools\Html;
use \Bitrix\Main\ORM\Objectify\EntityObject;
use \Bitrix\Main\ORM\Fields;

abstract class ScalarField extends Field
{
	public static function buildInput(Fields\Field $field, EntityObject $entity, $table, $tagData = [])
	{
		if ($field->getParameter('bxmod_readonly') === true)
		{
			return htmlspecialcharsbx($entity[$field->getName()]);
		}
		else
		{
			return Html::buildSimpleTag('input', $tagData + [
				'name' => $field->getName(),
				'value' => $entity->get($field->getName()),
				'type' => 'text',
			]);
		}
	}
}
