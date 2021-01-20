<?php

namespace MashinaMashina\Bxmod\Admin\Form\Builders;

use \MashinaMashina\Bxmod\Tools\Html;

abstract class ScalarField extends Field
{
	public static function buildInput($field, $entity, $table, $tagData = [])
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
