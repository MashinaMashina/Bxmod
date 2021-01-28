<?php

namespace Bxmod\Example;

use \Bitrix\Main\Localization\Loc;
use \MashinaMashina\Bxmod\Orm\Entity\DataManager;
use \MashinaMashina\Bxmod\Orm\Fields;

Loc::loadMessages(__FILE__);

class StudentsGroupTable extends DataManager
{
	public static function getTableName()
	{
		return 'bxmod_students_groups';
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
			new Fields\StringField('NAME', [
				'required' => true,
			]),
			(new Fields\Relations\OneToMany(
				'STUDENTS',
				StudentsTable::class,
				'GROUP'
			))
				->configureJoinType('left')
				->setParameter('bxmod_hidden', true),
			(new Fields\Relations\OneToMany(
				'TARGETS',
				TargetsTable::class,
				'GROUP'
			))
				->configureJoinType('left')
				->setParameter('bxmod_relation_view_type', 'editor')
		];
	}
}