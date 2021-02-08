<?php
namespace MashinaMashina\Bxmod\Orm\Fields\Relations;

use \MashinaMashina\Bxmod\Admin\Form\Editors\Relations\ReferenceDrivers\SimpleDriver;

trait RelationTrait
{
	public function getAllReferences($filter = [])
	{
		$references = $this->getParameter('get_all_references_func');
		$refEntity = $this->getRefEntity();
		
		if (is_callable($references))
		{
			return $references($this, $refEntity, $filter);
		}
		
		return SimpleDriver::getReferences($this, $refEntity, $filter);
	}
}