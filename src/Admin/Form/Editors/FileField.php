<?php

namespace MashinaMashina\Bxmod\Admin\Form\Editors;

use \Bitrix\Main\ORM\Fields;

class FileField extends ScalarField
{
	public static function buildTag($name, $value, Fields\Field $field, $tagData = [])
	{
		return \CFile::InputFile($name, 99999, $value);
	}
}