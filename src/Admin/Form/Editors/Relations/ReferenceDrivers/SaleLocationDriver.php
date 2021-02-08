<?php

namespace MashinaMashina\Bxmod\Admin\Form\Editors\Relations\ReferenceDrivers;

use \Bitrix\Main\Entity\Query;

class SaleLocationDriver extends BaseDriver
{
	public static function getReferences($field, $refEntity, $filter = [])
	{	
		$query = new Query($refEntity);
		$query->setSelect(['ID', 'LNAME' => 'NAME.NAME']);
		$query->addFilter('NAME.LANGUAGE_ID', 'ru');
		
		if (isset($filter['query']))
		{
			$query->where('NAME.NAME', 'like', '%' . $filter['query'] .'%');
		}
		
		if (isset($filter['entity']))
		{
			if ($field instanceof \MashinaMashina\Bxmod\Orm\Fields\Relations\ManyToMany)
			{
				$elements = $filter['entity']->fill([$field->getName()])->getAll();
				
				$ids = [];
				foreach ($elements as $element)
				{
					$ids[] = $element->get('ID');
				}
				
				$query->addFilter('ID', $ids);
			}
			else
			{
				foreach ($filter['entity']->primary as $k => $v)
					$query->addFilter($k, $v);
			}
		}
		else
		{
			$query->setLimit(30);
		}
		
		$elements = $query->exec()->fetchAll();
		
		return array_combine(
			array_column($elements, 'ID'),
			array_column($elements, 'LNAME')
		);
	}
}