<?php

namespace MashinaMashina\Bxmod\Admin\Form\Builders;

use \MashinaMashina\Bxmod\Tools\Html;

class DateField extends ScalarField
{
	public static function buildInput($field, $entity, $table)
	{
		\CJSCore::Init(['jquery', 'date']);
		
		return Html::buildSimpleTag('input', [
			'name' => $field->getName(),
			'value' => $entity[$field->getName()],
			'type' => 'text',
			'onclick' => 'BX.calendar({node: this, field: this, bTime: false})',
		]);
	}
}