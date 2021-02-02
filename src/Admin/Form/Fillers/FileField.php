<?php

namespace MashinaMashina\Bxmod\Admin\Form\Fillers;

use \Bitrix\Main\ORM\Objectify\EntityObject;
use \Bitrix\Main\ORM\Fields;
use \Bitrix\Main\HttpRequest;

class FileField extends ScalarField
{
	public static function getValueFromRequest(HttpRequest $request, Fields\Field $field)
	{
		$del = $request->getPost($field->getName() . '_del');
		
		if ($del === 'Y')
		{
			return 0;
		}
		
		$file = $request->getFile($field->getName());
		
		if (empty($file['tmp_name']))
		{
			return null;
		}
		
		return $file;
	}
	
	public static function fillEntity(EntityObject $entity, Fields\Field $field, $value)
	{
		if (is_numeric($value))
		{
			$fileId = $value;
		}
		elseif (is_array($value))
		{
			$moduleId = $entity->sysGetEntity()->getModule();
			
			$fileId = \CFile::SaveFile([
				'del' => '',
				'MODULE_ID' => $moduleId,
			] + $value, $moduleId);
		}
		else
		{
			$fileId = 0;
		}
		
		$entity->set($field->getName(), $fileId);
	}
}