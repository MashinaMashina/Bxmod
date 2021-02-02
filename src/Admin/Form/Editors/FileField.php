<?php

namespace MashinaMashina\Bxmod\Admin\Form\Editors;

use \Bitrix\Main\ORM\Objectify\EntityObject;
use \Bitrix\Main\ORM\Fields;

class FileField extends ScalarField
{
	public static function buildInput(Fields\Field $field, EntityObject $entity, $table, $tagData = [])
	{
		if ($field->getParameter('bxmod_readonly') === true)
		{
			return '';
		}
		else
		{
			return \CFile::InputFile($field->getName(), 99999, $entity->get($field->getName()));
		}
	}
}