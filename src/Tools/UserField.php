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
		'enumeration' => 'StringField',
		'url' => 'StringField',
		'string' => 'StringField',
		'file' => 'StringField',
		'integer' => 'IntegerField',
		'double' => 'FloatField',
		'string_formatted' => 'StringField',
	];
	
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
			
			if ($field->isMultiple())
			{
				$field->setParameter('bxmod_type', 'TextField');
			}
			else
			{
				$field->setParameter('bxmod_type', self::ALIASES[$arUf['USER_TYPE_ID']]);
			}
			
			if (! empty($arUf['EDIT_FORM_LABEL']))
			{
				$field->configureTitle($arUf['EDIT_FORM_LABEL']);
			}
		}
	}
}