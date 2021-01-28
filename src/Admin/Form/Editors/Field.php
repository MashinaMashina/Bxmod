<?php

namespace MashinaMashina\Bxmod\Admin\Form\Editors;

use \Bitrix\Main\ORM\Objectify\EntityObject;
use \Bitrix\Main\ORM\Fields;

abstract class Field
{
	public static function build(Fields\Field $field, EntityObject $entity, $table, $tagData = [])
	{
		if ($field->getParameter('bxmod_readonly') === true and is_null($entity[$field->getName()]))
		{
			return '';
		}
		
		$fieldName = htmlspecialcharsbx($field->getTitle());
		
		if ($field->getParameter('required'))
		{
			$fieldName = '<b>' . $fieldName . '</b>'; 
		}
		
		$result = '<tr><td width="40%">';
		$result .= $fieldName;
		$result .= '</td><td width="60%">';
		$result .= static::buildInput($field, $entity, $table, $tagData);
		$result .= '</td></tr>';
		
		return $result;
	}
	
	abstract public static function buildInput(Fields\Field $field, EntityObject $entity, $table, $tagData = []);
}
