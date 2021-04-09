<?php
namespace MashinaMashina\Bxmod\Orm\Entity;

use \Bitrix\Main\ORM\Data;

class DataManager extends Data\DataManager
{
	public static function getCacheTag()
	{
		return static::getTableName();
	}
	
	public static function getDbIndexes()
	{
		$fields = static::getMap();
		
		$indexes = [];
		foreach ($fields as $field)
		{
			if ($field->getParameter('bxmod_index') === true)
			{
				$indexes[] = $field->getName();
			}
		}
		
		return $indexes;
	}
}