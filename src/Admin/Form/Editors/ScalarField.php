<?php

namespace MashinaMashina\Bxmod\Admin\Form\Editors;

use \Bitrix\Main\ORM\Objectify\EntityObject;
use \Bitrix\Main\ORM\Fields;
use \Bitrix\Main\Localization\Loc;
use \MashinaMashina\Bxmod\Tools\Html;

abstract class ScalarField extends Field
{
	public static function buildInput(Fields\Field $field, EntityObject $entity, $table, $tagData = [])
	{
		$isMultiple = false;
		
		if (method_exists($field, 'isMultiple') and $field->isMultiple())
		{
			$isMultiple = true;
		}
		
		$name = $field->getName();
		$values = $entity->get($name);
		
		if (! is_array($values))
		{
			$values = [$values];
		}
		
		if ($field->getParameter('bxmod_readonly') === true)
		{
			foreach ($values as &$val)
			{
				$val = htmlspecialcharsbx($val);
			}
			return implode('<br>', $values);
		}
		else
		{
			$uniqId = uniqId('input-');
			$result = '<div id="'.$uniqId.'">';
			
			$i = 0;
			foreach ($values as &$val)
			{
				$_name = $name;
				
				if ($isMultiple)
				{
					$_name .= '[n'.$i.']';
				}
				
				$result .= static::buildTag($_name, $val, $field, $tagData) . '<br />';
				$i++;
			}
			
			$result .= '</div>';
			
			if ($isMultiple)
			{
				$_name = $name;
				
				if ($isMultiple)
				{
					$_name .= '[n#NUM#]';
				}
				
				$result .= static::buildAddingInputButton($uniqId, static::buildTag($_name, '', $field, $tagData)  . '<br />');
			}
			return $result;
		}
	}
	
	public static function buildTag($name, $value, Fields\Field $field, $tagData = [])
	{
		return Html::buildSimpleTag('input', $tagData + [
			'name' => $name,
			'value' => $value,
			'type' => 'text',
		]);
	}
	
	protected static function buildAddingInputButton($containerId, $template)
	{
		$templateId = $containerId . '-tpl';
		$result = '<template id="'.$templateId.'">'.$template.'</template>';
		$result .= '<input type="button" onclick="bxMod.addTemplateToContainer(\''.$containerId.'\', \''.$templateId.'\')" value="'.Loc::getMessage('bxmod_add').'">';
		
		return $result;
	}
}
