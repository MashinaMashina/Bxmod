<?php

namespace Bxmod\Example;

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Orm\Query\Join;
use \MashinaMashina\Bxmod\Orm\Entity\DataManager;
use \MashinaMashina\Bxmod\Orm\Fields;

Loc::loadMessages(__FILE__);

class TargetsTable extends DataManager
{
	public static function getTableName()
	{
		return 'bxmod_targets';
	}
	
	public static function getMap()
	{
		return [
			new Fields\IntegerField('ID', [
				'primary' => true,
				'autocomplete' => true,
				'bxmod_readonly' => true,
			]),
			new Fields\StringField('NAME', [
				'required' => true,
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
			))
				->setParameter('bxmod_hidden', true),
		];
	}
}