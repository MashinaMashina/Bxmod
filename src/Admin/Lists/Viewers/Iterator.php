<?php

namespace MashinaMashina\Bxmod\Admin\Lists\Viewers;

use \Bitrix\Main\ORM\Entity;
use \Bitrix\Main\ORM\Objectify\EntityObject;

class Iterator
{
	public static function prepareView(Entity $entityTable, EntityObject $entity)
	{
		$avaibledFields = $entityTable->getFields();
		$result = [];
		foreach ($avaibledFields as $field)
		{
			if ($field->getParameter('bxmod_hidden') === true)
				continue;
			
			$name = $field->getName();
			if (! $entity->has($name))
			{
				continue;
			}
			
			$value = $entity->get($name);
			$result[$name] = ($field->getViewerClass())::prepareView($entityTable, $entity, $value);
		}
		
		return $result;
	}
}