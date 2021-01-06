<?php

namespace MashinaMashina\Bxmod\Admin\Builders;

use \MashinaMashina\Bxmod\Tools\Html;

abstract class ScalarField extends Field
{
	public static function buildInput($field, $entity, $table)
	{
		if ($field->getParameter('bxmod_readonly') === true)
		{
			return htmlspecialcharsbx($entity[$field->getName()]);
		}
		else
		{
			return Html::buildSimpleTag('input', [
				'name' => $field->getName(),
				'value' => $entity[$field->getName()],
				'type' => 'text',
			]);
		}
	}
}
