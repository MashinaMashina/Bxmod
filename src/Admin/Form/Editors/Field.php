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
		
		$result = '<tr>';
		$result .= '<td width="40%">';
		$result .= $fieldName;
		$result .= '</td>';
		$result .= '<td width="60%">';
		$result .= '<div class="bxmod-input-block">';
		$result .= static::buildInput($field, $entity, $table, $tagData);
		$result .= '</div>';
		$result .= '<div class="bxmod-help-block">';
		$result .= $field->getParameter('bxmod_description');
		$result .= '</div>';
		$result .= '</td>';
		$result .= '</tr>' . PHP_EOL;
		
		return $result;
	}
	
	abstract public static function buildInput(Fields\Field $field, EntityObject $entity, $table, $tagData = []);
}
