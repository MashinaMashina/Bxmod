<?php
namespace MashinaMashina\Bxmod\Admin;

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;
use \MashinaMashina\Bxmod\Tools\AssetsManager;
use \MashinaMashina\Bxmod\Orm\Entity\DataManager;

abstract class BaseGenerator
{
	protected $entityClass;
	protected $entity;
	protected $request;
	protected $messages;
	protected $formLink;
	protected $listLink;
	protected $primaryCode = 'ID';
	protected $langMessages = [];
	
	const MESS_ERROR = 'ERROR';
	const MESS_OK = 'OK';
	
	public function __construct(DataManager $entityClass)
	{
		$this->entityClass = $entityClass;
		$this->request = Application::getInstance()->getContext()->getRequest();
		$this->primaryCode = $this->entityClass->getEntity()->getPrimary();
		$this->langMessages = Loc::loadLanguageFile(__FILE__);
		
		AssetsManager::init();
	}
	
	public function init($formLink, $listLink)
	{
		$this->formLink = $formLink;
		$this->listLink = $listLink;
	}
	
	abstract function generate();
	abstract function display();
	
	public function setLangMessage($key, $value = '')
	{
		if (is_array($key))
		{
			foreach ($key as $k => $v)
			{
				$this->setLangMessage($k, $v);
			}
			return;
		}
		
		$this->langMessages['bxmod_' . $key] = $value;
	}
	
	public function getLangMessage($key)
	{
		return isset($this->langMessages['bxmod_' . $key])
			? $this->langMessages['bxmod_' . $key]
			: $key;
	}
	
	public function formLink($data = [])
	{
		$data['lang'] = LANG;
		
		return $this->formLink . '?' . http_build_query($data);
	}
	
	public function goToForm($data = [])
	{
		LocalRedirect($this->formLink($data));
		exit;
	}
	
	public function listLink($data = [])
	{
		$data['lang'] = LANG;
		
		return $this->listLink . '?' . http_build_query($data);
	}
	
	public function goToList($data = [])
	{
		LocalRedirect($this->listLink($data));
		exit;
	}
	
	/*
	 *	@param string $type in list: [FormGenerator::MESS_OK, FormGenerator::MESS_OK]
	 */
	public function addMessage($message, $type = '')
	{
		$this->messages[] = new \CAdminMessage([
			'TYPE' => $type,
			'MESSAGE' => $message,
		]);
	}
	
	public function getMessages()
	{
		return $this->messages;
	}
	
	public function checkPermissions($moduleName, $needsPerm)
	{
		global $APPLICATION;
		
		if ($APPLICATION->GetGroupRight($moduleName) < $needsPerm)
			$APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
	}
	
}