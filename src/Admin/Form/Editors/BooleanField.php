<?php

namespace MashinaMashina\Bxmod\Admin\Form\Editors;

use \MashinaMashina\Bxmod\Tools\Html;

class BooleanField extends ScalarField
{
	public static function buildInput($field, $entity, $table, $tagData = [])
	{
		$checked = ($entity->get($field->getName()) ? 'checked' : '');
		
		$str = Html::buildSimpleTag('input', [
			'name' => $field->getName(),
			'type' => 'hidden',
			'value' => 'N',
		]);
		
		return $str . Html::buildSimpleTag('input', $tagData + [
			'name' => $field->getName(),
			'type' => 'checkbox',
			'value' => 'Y',
			$checked => '',
		]);
	}
}
