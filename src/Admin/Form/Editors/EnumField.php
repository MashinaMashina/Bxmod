<?php

namespace MashinaMashina\Bxmod\Admin\Form\Editors;

use \MashinaMashina\Bxmod\Tools\Html;
use \Bitrix\Main\ORM\Fields;

class EnumField extends ScalarField
{
	public static function buildTag($name, $value, Fields\Field $field, $tagData = [])
	{
		// Для пользовательских полей индекс значения - это ключ
		$valueIsKey = $field->getParameter('bxmod_uf_type') ? false : true;
		
		$options = '';
		foreach ($field->getParameter('values') as $key => $option)
		{
			$optionValue = $valueIsKey ? $option : $key;
			$selected = ($optionValue === $value ? 'selected' : '');
			
			$options .= Html::buildTag('option', [
				'value' => $optionValue,
				$selected => '',
			], $option);
		}
		
		return Html::buildTag('select', $tagData + [
			'name' => $name,
		], $options);
	}
}