<?php

namespace Bxmod\Example;

use \Bitrix\Main\Loader;
use \Bitrix\Main\Orm\Query\Join;
use \Bitrix\Main\Entity\Query;
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
		$fields = [
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
		
		if (Loader::includeModule('catalog'))
		{
			$fields[] = new Fields\IntegerField('PRODUCT_ID', [
				'bxmod_hidden' => true,
			]);
			
			$fields[] = (new Fields\Relations\Reference(
				'PRODUCT',
				\Bitrix\Catalog\ProductTable::class,
                Join::on('this.PRODUCT_ID', 'ref.ID')
			))
			->setParameter('bxmod_relation_view_type', 'editor')
			->setParameter('get_all_references_func', function($field, $refEntity){
				$query = new Query($refEntity);
				$query->setSelect(['ID', 'NAME' => 'IBLOCK_ELEMENT.NAME']);
				$elements = $query->exec()->fetchAll();
				
				return array_combine(
					array_column($elements, 'ID'),
					array_column($elements, 'NAME')
				);
			});
		}
		
		return $fields;
	}
}