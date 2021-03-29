<?php

namespace MashinaMashina\Bxmod\Admin\Form\Editors;

use \MashinaMashina\Bxmod\Tools\Html;
use \Bitrix\Main\ORM\Objectify\EntityObject;
use \Bitrix\Main\ORM\Fields;

class DatetimeField extends DateField
{
	public static function buildInput(Fields\Field $field, EntityObject $entity, $table, $tagData = [])
	{
		\CJSCore::Init(['jquery', 'date']);
		
		return Html::buildSimpleTag('input', $tagData + [
			'name' => $field->getName(),
			'value' => $entity[$field->getName()],
			'type' => 'text',
			'onclick' => 'BX.calendar({node: this, field: this, bTime: true})',
		]);
	}
}