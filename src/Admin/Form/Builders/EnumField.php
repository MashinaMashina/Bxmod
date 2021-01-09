<?php

namespace MashinaMashina\Bxmod\Admin\Form\Builders;

use \MashinaMashina\Bxmod\Tools\Html;

class EnumField extends ScalarField
{
	public static function buildInput($field, $entity, $table, $tagData = [])
	{
		$options = '';
		foreach ($field->getParameter('values') as $value)
		{
			$selected = ($value === $tagData['value'] ? 'selected' : '');
			
			$options .= Html::buildTag('option', [
				'value' => $value,
				$selected => '',
			], $value);
		}
		
		return Html::buildTag('select', $tagData + [
			'name' => $field->getName(),
		], $options);
	}
}