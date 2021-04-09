<?php

namespace MashinaMashina\Bxmod\Admin\Form\Editors;

use \MashinaMashina\Bxmod\Tools\Html;
use \Bitrix\Main\ORM\Fields;

class TextField extends StringField
{
	public static function buildTag($name, $value, Fields\Field $field, $tagData = [])
	{
		return Html::buildTag('textarea', $tagData + [
			'name' => $name,
		], htmlentities($value));
	}
}