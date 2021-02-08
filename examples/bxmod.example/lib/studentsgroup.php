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
		
		if (Loader::includeModule('catalog') and Loader::includeModule('sale'))
		{
			$fields[] = new Fields\IntegerField('PRODUCT_ID', [
				'bxmod_hidden' => true,
			]);
			
			$fields[] = (new Fields\Relations\Reference(
				'PRODUCT',
				\Bitrix\Catalog\ProductTable::class,
                Join::on('this.PRODUCT_ID', 'ref.ID')
			))
				->setParameter('get_all_references_func', function($field, $refEntity){
					return \MashinaMashina\Bxmod\Admin\Form\Editors\Relations\ReferenceDrivers\CatalogDriver::getReferences($field, $refEntity, $filter);
				});
			/*
			$fields[] = (new Fields\Relations\ManyToMany('LOCATIONS', \Bitrix\Sale\Location\LocationTable::class))
                ->configureTableName('bxmod_students_groups_locations')
				->setParameter('bxmod_relation_view_type', 'ajax_select')
				->setParameter('get_all_references_func', function($field, $refEntity, $filter){
					return \MashinaMashina\Bxmod\Admin\Form\Editors\Relations\ReferenceDrivers\SaleLocationDriver::getReferences($field, $refEntity, $filter);
				});
				*/
		}
		
		return $fields;
	}
}