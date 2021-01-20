<?php
namespace MashinaMashina\Bxmod\Orm\Fields\Relations;

use \Bitrix\Main\Entity\Query;

trait RelationTrait
{
	public function getAllReferences()
	{
		$references = $this->getParameter('get_all_references_func');
		$refEntity = $this->getRefEntity();
		
		if (is_callable($references))
		{
			return $references($this, $refEntity);
		}
		
		$query = new Query($refEntity);
		$query->setSelect(['ID', 'NAME']);
		$elements = $query->exec()->fetchAll();
		
		return array_combine(
			array_column($elements, 'ID'),
			array_column($elements, 'NAME')
		);
	}
}