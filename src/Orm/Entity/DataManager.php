<?php
namespace MashinaMashina\Bxmod\Orm\Entity;

use \Bitrix\Main\Entity;

class DataManager extends Entity\DataManager
{
	public static function getCacheTag()
	{
		return static::getTableName();
	}
	
	public static function getDbIndexes()
	{
		return [];
	}
}