<?php

namespace MashinaMashina\Bxmod\Admin\Builders;

use \MashinaMashina\Bxmod\Tools\Html;

class EnumField extends ScalarField
{
	public static function buildInput($field, $entity, $table)
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
		
		return Html::buildTag('select', [
			'name' => $field->getName(),
		], $options);
	}
}