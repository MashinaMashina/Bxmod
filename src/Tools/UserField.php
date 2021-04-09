<?php
namespace MashinaMashina\Bxmod\Tools;

use \Bitrix\Main\ORM\Fields;

class UserField
{
	const ALIASES = [
		'address' => 'StringField',
		'video' => 'StringField',
		'boolean' => 'BooleanField',
		'date' => 'DateField',
		'datetime' => 'DatetimeField',
		'vote' => 'StringField',
		'iblock_section' => 'StringField',
		'hlblock' => 'StringField',
		'iblock_element' => 'StringField',
		'url_preview' => 'StringField',
		'enumeration' => 'EnumField',
		'url' => 'StringField',
		'string' => 'StringField',
		'file' => 'FileField',
		// 'file' => 'StringField',
		'integer' => 'IntegerField',
		'double' => 'FloatField',
		'string_formatted' => 'StringField',
	];
	
	/*
	 * Дополняет пользовательское поле необходимыми даными
	 */
	public static function fillUfFieldInfo(Fields\Field $field)
	{
		$fieldName = $field->getName();
		$entityId = $field->getEntity()->getUfId();
		
		$res = \CUserTypeEntity::GetList([], [
			'ENTITY_ID' => $entityId,
			'FIELD_NAME' => $fieldName,
			'LANG' => LANGUAGE_ID,
		]);
		
		if ($arUf = $res->fetch())
		{
			if ($arUf['MANDATORY'] === 'Y')
			{
				$field->setParameter('required', true);
			}
			
			if (! empty($arUf['EDIT_FORM_LABEL']))
			{
				$field->configureTitle($arUf['EDIT_FORM_LABEL']);
			}
			if (! empty($arUf['HELP_MESSAGE']))
			{
				$field->setParameter('bxmod_description', $arUf['HELP_MESSAGE']);
			}
			
			$field->setParameter('bxmod_uf_type', self::ALIASES[$arUf['USER_TYPE_ID']]);
			
			if ($arUf['USER_TYPE_ID'] === 'enumeration')
			{
				$res = \CUserFieldEnum::GetList(['DEF' => 'DESC'], [
					'USER_FIELD_ID' => $arUf['ID'],
				]);
				
				$options = [];
				while($arOption = $res->fetch())
				{
					$options[$arOption['ID']] = $arOption['VALUE'];
				}
				
				$field->setParameter('values', $options);
			}
			
			return $arUf;
		}
		
		return null;
	}
}