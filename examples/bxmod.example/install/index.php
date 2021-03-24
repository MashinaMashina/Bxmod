<?php

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;
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
		require __DIR__ . '/version.php';
		require_once __DIR__ . '/../lib/students.php';
		require_once __DIR__ . '/../lib/studentsgroup.php';
		require_once __DIR__ . '/../lib/targets.php';
		
		$this->MODULE_VERSION = $arModuleVersion['VERSION'];
		$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		$this->MODULE_NAME = Loc::getMessage('BXMOD_EXAMPLE_NAME');
		
		$this->PARTNER_NAME = Loc::getMessage("BXMOD_PARTNER");
		$this->PARTNER_URI = 'https://github.com/MashinaMashina/Bxmod';
		
		$this->addModuleEntity(\Bxmod\Example\StudentsTable::class);
		$this->addModuleEntity(\Bxmod\Example\StudentsGroupTable::class);
		$this->addModuleEntity(\Bxmod\Example\TargetsTable::class);
		
		/*
		 *Пример добавления события сущности
		 *
		
		$studentClass = '\\' . substr(\Bxmod\Example\StudentsTable::class, 0, -5); // Удалим суффикс "Table"
		
		$this->addModuleEvent(
			$this->MODULE_ID,
			$studentClass . '::onBeforeAdd',
			$this->MODULE_ID,
			\Bxmod\Example\Studentshandler::class, "onStudentBeforeAdd"
		);
		*/
	}
	
	public function DoInstall()
	{
		RegisterModule($this->MODULE_ID);
		
		$oldVersion = Option::get($this->MODULE_ID, 'INSTALLED_VERSION');
		if ($oldVersion)
		{
			$this->migrate(__DIR__ . '/migrations/', $oldVersion);
		}
		else
		{
			$this->InstallEntities();
		}
		
		$this->InstallEvents();
		CopyDirFiles(__DIR__ . '/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin', true);
		
		Option::set($this->MODULE_ID, 'INSTALLED_VERSION', $this->MODULE_VERSION);
	}
	
	public function DoUninstall()
	{
		global $APPLICATION;
		
		if($_REQUEST['step'] < 2)
		{
			$APPLICATION->IncludeAdminFile(Loc::getMessage("BXMOD_UNINSTALL_TITLE"), __DIR__ . '/unstep1.php');
			return;
		}
		
		$saveData = $_REQUEST['savedata'] === 'Y';
		
		DeleteDirFiles(__DIR__ . '/admin/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin');
		
		$this->UninstallEvents();
		
		if (! $saveData)
		{
			$this->UninstallEntities();
			Option::delete($this->MODULE_ID, ['name' => 'INSTALLED_VERSION']);
		}
		
		UnRegisterModule($this->MODULE_ID);
	}
}