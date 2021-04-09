<?php

namespace MashinaMashina\Bxmod\Admin\Form\Editors;

use \MashinaMashina\Bxmod\Tools\Html;
use \Bitrix\Main\ORM\Fields;

class DatetimeField extends DateField
{
	public static function buildTag($name, $value, Fields\Field $field, $tagData = [])
	{
		\CJSCore::Init(['jquery', 'date']);
		
		return Html::buildSimpleTag('input', $tagData + [
			'name' => $name,
			'value' => $value,
			'type' => 'text',
			'onclick' => 'BX.calendar({node: this, field: this, bTime: true})',
		]);
	}
}