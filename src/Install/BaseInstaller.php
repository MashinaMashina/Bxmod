<?php
namespace MashinaMashina\Bxmod\Install;

use \Bitrix\Main;
use \Bitrix\Main\ORM\Fields\FieldTypeMask;

class BaseInstaller extends \CModule
{
	private $events = [];
	private $entities = [];
	
	public function addModuleEvent(string $fromModuleName, string $fromModuleEvent, string $toModule, string $toClass, string $toFunction)
	{
		$this->events[] = func_get_args();
	}
	
	public function InstallEvents()
	{
		$eventManager = Main\EventManager::getInstance();
		
		foreach ($this->getModuleEvents() as $event)
			call_user_func_array([$eventManager, 'registerEventHandler'], $event);
	}
	
	public function UninstallEvents()
	{
		$eventManager = Main\EventManager::getInstance();
		
		foreach ($this->getModuleEvents() as $event)
			call_user_func_array([$eventManager, 'unRegisterEventHandler'], $event);
	}
	
	protected function getModuleEvents()
	{
		return $this->events;
	}
	
	public function addModuleEntity(string $entityClass)
	{
		$this->entities[] = $entityClass;
	}
	
	public function getModuleEntities()
	{
		return $this->entities;
	}
	
	public function InstallEntities($entities = false)
	{
		if (! $entities)
			$entities = $this->getModuleEntities();
		
		foreach ($entities as $entityClass)
		{
			$entity = ($entityClass)::getEntity();
			$tableName = ($entityClass)::getTableName();
			
			$connection = Main\Application::getConnection();
			$sqlHelper = $connection->getSqlHelper();
			$tableExists = $connection->isTableExists($tableName);
			
			if (! $tableExists)
			{
				$entity->createDBTable();
				
				$fields = $entity->getFields();
				
				$indexes = ($entityClass)::getDbIndexes();
				foreach ($indexes as $column)
				{
					$quotedIndexName = $sqlHelper->quote("{$tableName}_{$column}");
					$quotedTableName = $sqlHelper->quote($tableName);
					$quotedColumn = $sqlHelper->quote($column);
					$connection->query("CREATE INDEX {$quotedIndexName} ON {$quotedTableName} ({$quotedColumn})");
				}
				
				foreach ($fields as $field)
				{
					if ($field->getTypeMask() === FieldTypeMask::MANY_TO_MANY)
					{
						$mediator = $field->getMediatorEntity();
						if (! $connection->isTableExists($mediator->getDBTableName()))
						{
							$mediator->createDbTable();
						}
					}
				}
			}
		}
	}
	
	public function UninstallEntities()
	{
		$connection = Main\Application::getConnection();
		$sqlHelper = $connection->getSqlHelper();
		
		foreach ($this->getModuleEntities() as $entityClass)
		{
			$tableName = ($entityClass)::getTableName();
			$tableName = $sqlHelper->quote($tableName);
		
			$connection->query("DROP TABLE IF EXISTS {$tableName}");
			
			$fields = ($entityClass)::getEntity()->getFields();
			
			foreach ($fields as $field)
			{
				if ($field->getTypeMask() === FieldTypeMask::MANY_TO_MANY)
				{
					$tableName = $field->getMediatorEntity()->getDBTableName();
					$tableName = $sqlHelper->quote($tableName);
					$connection->query("DROP TABLE IF EXISTS {$tableName}");
				}
			}
		}
	}
	
	public function clearComponentsCache()
	{
		Main\Loader::IncludeModule('fileman');
		\CHTMLEditor::GetComponents([], true);
	}
	
	protected function Migrate($dir, $oldVersion)
	{
		$migrations = scandir($dir);
		$oldVersion .= '.php';
		
		foreach ($migrations as $migrateFile)
		{
			if ($migrateFile === '.' or $migrateFile === '..') continue;
			if (version_compare($migrateFile, $oldVersion) <= 0) continue;
			
			require $dir . $migrateFile; 
		}
	}
}