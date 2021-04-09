<?php

namespace Bxmod\Example;

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Orm\Query\Join;
use \MashinaMashina\Bxmod\Orm\Entity\DataManager;
use \MashinaMashina\Bxmod\Orm\Fields;

Loc::loadMessages(__FILE__);

class StudentsTable extends DataManager
{
	public static function getTableName()
	{
		return 'bxmod_students';
	}
	
	public static function getUfId()
    {
        return 'MY_STUDENT';
    }
	
	public static function getMap()
	{
		return [
			new Fields\IntegerField('ID', [
				'primary' => true,
				'autocomplete' => true,
				'bxmod_readonly' => true,
			]),
			new Fields\BooleanField('ACTIVE', [
				'default_value' => 1,
				'bxmod_index' => true,
			]),
			new Fields\StringField('FIRST_NAME', [
				'required' => true,
			]),
			new Fields\StringField('LAST_NAME', [
				'required' => true,
			]),
			new Fields\DateField('BIRTHDAY', [
			]),
			new Fields\FloatField('HEIGHT', [
			]),
			new Fields\EnumField('SEX', [
				'values' => ['M', 'F'],
			]),
			new Fields\TextField('DESCRIPTION', [
			]),
			new Fields\IntegerField('GROUP_ID', [
				'bxmod_hidden' => true,
			]),
			(new Fields\Relations\Reference(
				'GROUP',
				StudentsGroupTable::class,
                Join::on('this.GROUP_ID', 'ref.ID')
			)),
			new Fields\FileField('AVATAR', [
			]),
		];
	}
}