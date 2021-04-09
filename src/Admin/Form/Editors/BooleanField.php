<?php

namespace MashinaMashina\Bxmod\Admin\Form\Editors;

use \MashinaMashina\Bxmod\Tools\Html;
use \Bitrix\Main\ORM\Fields;

class BooleanField extends ScalarField
{
	public static function buildTag($name, $value, Fields\Field $field, $tagData = [])
	{
		$checked = ($value ? 'checked' : '');
		
		$str = Html::buildSimpleTag('input', [
			'name' => $name,
			'type' => 'hidden',
			'value' => 'N',
		]);
		
		return $str . Html::buildSimpleTag('input', $tagData + [
			'name' => $name,
			'type' => 'checkbox',
			'value' => 'Y',
			$checked => '',
		]);
	}
}
