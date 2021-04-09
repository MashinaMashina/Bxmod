<?php

namespace MashinaMashina\Bxmod\Admin\Form\Fillers;

use \Bitrix\Main\ORM\Objectify\EntityObject;
use \Bitrix\Main\ORM\Fields;
use \Bitrix\Main\HttpRequest;
use \Bitrix\Main\UI\FileInputUtility;
use \Bitrix\Main\ORM\Fields\UserTypeField;
use \MashinaMashina\Bxmod\Tools\UserField;

class FileField extends ScalarField
{
	protected static $multipleFields = [];
	
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
		
		if (is_array($file['tmp_name']))
		{
			self::$multipleFields[] = $field->getName();
			$files = [];
			foreach ($file as $paramName => $paramValues)
			{
				foreach ($paramValues as $index => $value)
				{
					if (! empty($file['tmp_name'][$index]))
					{
						$files[$index][$paramName] = $value;
					}
					elseif (isset($del[$index]) and $del[$index] === 'Y')
					{
						$files[$index] = 0;
					}
				}
			}
			
			return count($files) ? $files : null;
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
			$isMultiple = in_array($field->getName(), self::$multipleFields);
			$files = $entity->get($field->getName());
			
			if (! $isMultiple)
			{
				$value = ['n0' => $value];
				$files = [$files];
			}
			foreach ($value as $key => $file)
			{
				$numericKey = substr($key, 1); // n1 to 1
				if ($file === 0)
				{
					if ($files[$numericKey] > 0)
					{
						\CFile::Delete($files[$numericKey]);
					}
					
					unset($files[$numericKey]);
				}
				elseif (is_numeric($file))
				{
					$files[$numericKey] = $file;
				}
				else
				{
					$fileId = \CFile::SaveFile([
						'del' => '',
						'MODULE_ID' => $moduleId,
					] + $file, $moduleId);
					
					/* добавляем файл в разрешенные к сохранению */
					if ($field instanceof UserTypeField)
					{
						$arUserField = UserField::fillUfFieldInfo($field);
						
						$fileInput = FileInputUtility::instance();
						$controlId = $fileInput->getUserFieldCid($arUserField);
						$CID = $fileInput->registerControl($controlId);
						$fileInput->registerFile($CID, $fileId);
					}
					var_dump($files, $numericKey, $fileId);
					$files[$numericKey] = $fileId;
				}
			}
			
			$fileId = $isMultiple ? $files : array_shift($files);
		}
		else
		{
			$oldFileId = $entity->get($field->getName());
			$fileId = null;
			
			if ($oldFileId > 0)
			{
				\CFile::Delete($oldFileId);
			}
		}
		
		$entity->set($field->getName(), $fileId);
	}
}