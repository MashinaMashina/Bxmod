<?php

namespace MashinaMashina\Bxmod\Admin\Form\Editors\Relations\ReferenceDrivers;

use \Bitrix\Main\Entity\Query;

class CatalogDriver extends BaseDriver
{
	public static function getReferences($field, $refEntity, $filter = [])
	{
		$query = new Query($refEntity);
		$query->setSelect(['ID', 'NAME' => 'IBLOCK_ELEMENT.NAME']);
		
		if (isset($filter['query']))
		{
			$query->where('IBLOCK_ELEMENT.NAME', 'like', '%' . $filter['query'] .'%');
		}
		if (isset($filter['entity']))
		{
			foreach ($filter['entity']->primary as $k => $v)
				$query->addFilter($k, $v);
		}
		else
		{
			$query->setLimit(30);
		}
		
		$elements = $query->exec()->fetchAll();
		
		return array_combine(
			array_column($elements, 'ID'),
			array_column($elements, 'NAME')
		);
	}
}