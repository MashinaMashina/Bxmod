<?php

namespace MashinaMashina\Bxmod\Admin\Builders;

abstract class Field
{
	public static function build($field, $entity, $table)
	{
		$fieldName = htmlspecialcharsbx($field->getTitle());
		
		if ($field->getParameter('required'))
		{
			$fieldName = '<b>' . $fieldName . '</b>'; 
		}
		
		$result = '<tr><td width="40%">';
		$result .= $fieldName;
		$result .= '</td><td width="60%">';
		$result .= static::buildInput($field, $entity, $table);
		$result .= '</td></tr>';
		
		return $result;
	}
	
	abstract public static function buildInput($field, $entity, $table);
}
