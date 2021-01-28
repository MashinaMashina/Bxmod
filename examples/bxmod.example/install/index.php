<?php

use \Bitrix\Main\Localization\Loc;
use \MashinaMashina\Bxmod\Install\BaseInstaller;

class bxmod_example extends BaseInstaller
{
	public $MODULE_ID = 'bxmod.example';
	public $MODULE_NAME;
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_GROUP_RIGHTS = 'Y';
	
	public function __construct()
	{
		Loc::loadMessages(__FILE__);
		require_once __DIR__ . '/version.php';
		require_once __DIR__ . '/../lib/students.php';
		require_once __DIR__ . '/../lib/studentsgroup.php';
		require_once __DIR__ . '/../lib/targets.php';
		
		$this->MODULE_VERSION = $arModuleVersion['VERSION'];
		$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		$this->MODULE_NAME = GetMessage('BXMOD_EXAMPLE_NAME');
		
		$this->PARTNER_NAME = GetMessage("BXMOD_PARTNER");
		$this->PARTNER_URI = 'https://github.com/MashinaMashina/Bxmod';
		
		$this->addModuleEntity(\Bxmod\Example\StudentsTable::class);
		$this->addModuleEntity(\Bxmod\Example\StudentsGroupTable::class);
		$this->addModuleEntity(\Bxmod\Example\TargetsTable::class);
		
		// $studentClass = '\\' . substr(\Morozov\Nyamus\ShipmentsTable::class, 0, -5); // Удалим суффикс "Table"
		
		// $this->addModuleEvent(
			// $this->MODULE_ID,
			// $studentClass . '::onBeforeAdd',
			// $this->MODULE_ID,
			// \Morozov\Nyamus\ShipmentsHandler::class, "onShipmentBeforeAdd"
		// );
	}
	
	public function DoInstall()
	{
		RegisterModule($this->MODULE_ID);
		
		$this->InstallEntities();
		$this->InstallEvents();
		CopyDirFiles(__DIR__ . '/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin', true);
	}
	
	public function DoUninstall()
	{
		DeleteDirFiles(__DIR__ . '/admin/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin');
		
		$this->UninstallEvents();
		$this->UninstallEntities();
		
		UnRegisterModule($this->MODULE_ID);
	}
}