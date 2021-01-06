<?php

namespace MashinaMashina\Bxmod\Admin\Builders;

use \MashinaMashina\Bxmod\Tools\Html;

class BooleanField extends ScalarField
{
	public static function buildInput($field, $entity, $table)
	{
		$checked = ($tagData['value'] ? 'checked' : '');
		
		$str = Html::buildSimpleTag('input', [
			'name' => $field->getName(),
			'type' => 'hidden',
			'value' => 'N',
		]);
		
		return $str . Html::buildSimpleTag('input', [
			'name' => $field->getName(),
			'type' => 'checkbox',
			'value' => 'Y',
			$checked => '',
		]);
	}
}
