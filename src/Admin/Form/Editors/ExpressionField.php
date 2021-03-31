<?php

namespace MashinaMashina\Bxmod\Admin\Form\Editors;

use \MashinaMashina\Bxmod\Tools\Html;
use \Bitrix\Main\ORM\Objectify\EntityObject;
use \Bitrix\Main\ORM\Fields;

class ExpressionField extends Field
{
	public static function build(Fields\Field $field, EntityObject $entity, $table, $tagData = [])
	{
		return '';
	}
	
	public static function buildInput(Fields\Field $field, EntityObject $entity, $table, $tagData = [])
	{
		return htmlspecialcharsbx($entity[$field->getName()]);
	}
}

