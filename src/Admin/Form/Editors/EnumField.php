<?php

namespace MashinaMashina\Bxmod\Admin\Form\Editors;

use \MashinaMashina\Bxmod\Tools\Html;
use \Bitrix\Main\ORM\Objectify\EntityObject;
use \Bitrix\Main\ORM\Fields;

class EnumField extends ScalarField
{
	public static function buildInput(Fields\Field $field, EntityObject $entity, $table, $tagData = [])
	{
		$options = '';
		foreach ($field->getParameter('values') as $value)
		{
			$selected = ($value === $entity->get($field->getName()) ? 'selected' : '');
			
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