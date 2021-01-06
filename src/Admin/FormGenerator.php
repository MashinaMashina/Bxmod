<?php
namespace MashinaMashina\Bxmod\Admin;

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Type\Date;
use \Bitrix\Main\Application;
use \Bitrix\Main\ORM\Fields\FieldTypeMask;
use \MashinaMashina\Bxmod\Tools\Html;
use \MashinaMashina\Bxmod\Tools\AssetsManager;
use \MashinaMashina\Bxmod\Orm\Entity\DataManager;

class FormGenerator
{
	protected $entityClass;
	protected $entity;
	protected $request;
	protected $primaryKey;
	protected $messages;
	protected $formLink;
	protected $listLink;
	protected $primaryCode = 'ID';
	protected $topMenu = [];
	protected $tabs = [];
	
	const MESS_ERROR = 'ERROR';
	const MESS_OK = 'OK';
	
	public function __construct(DataManager $entityClass)
	{
		$this->entityClass = $entityClass;
		$this->request = Application::getInstance()->getContext()->getRequest();
		
		AssetsManager::init();
	}
	
	public function initForm($formLink, $listLink)
	{
		$this->formLink = $formLink;
		$this->listLink = $listLink;
		$this->primaryKey = (int) $this->request->getQuery($this->primaryCode);
	}
	
	public function getPrimaryKey()
	{
		$this->getEntity();
		
		return $this->primaryKey;
	}
	
	public function generateForm()
	{
		$this->executeForm();
		
		$tabControl = new \CAdminTabControl("tabControl", $this->getTabs());
		$context = new \CAdminContextMenu($this->getTopMenuItems());
		
		ob_start();
		$context->Show();
		
		foreach ($this->getMessages() as $message)
			echo $message->show();
		
		echo '<form method="POST" action="'.$this->formLink([$this->primaryCode => $this->primaryKey]).'" enctype="multipart/form-data">';
		echo bitrix_sessid_post();
		$tabControl->Begin();
		
		$avaibledFields = $this->entityClass->getEntity()->getFields();
		$lastTab = -1;
		$tabStarted = false;
		foreach ($avaibledFields as $field)
		{
			$tabName = $field->getParameter('bxmod_tab_number');
			
			if ($lastTab !== $tabName)
			{
				if ($tabStarted)
				{
					$tabControl->Buttons(
						array(
							"back_url" => $this->listLink(),
						)
					);
					
					$tabControl->end();
					$tabStarted = false;
				}
				
				$tabControl->beginNextTab();
				$lastTab = $tabName;
				$tabStarted = true;
			}
			
			echo $this->buildEditArea($field);
		}
		
		if ($tabStarted)
		{
			$tabControl->Buttons(
				array(
					"back_url" => $this->listLink(),
				)
			);
			
			$tabControl->end();
			$tabStarted = false;
		}
		
		echo '</form>';
		
		return ob_get_clean();
	}
	
	protected function buildEditArea($field)
	{
		if ($field->getParameter('bxmod_hidden') === true)
			return;
		
		$class = str_replace('MashinaMashina\Bxmod\ORM\Fields', 'MashinaMashina\Bxmod\Admin\Builders', get_class($field));
		
		echo ($class)::build($field, $this->getEntity(), $this->entityClass);
	}
	
	protected function getEntity()
	{
		if (! isset($this->entity))
		{
			if ($this->primaryKey > 0)
			{
				$this->entity = $this->entityClass->getList(['filter' => [
					$this->primaryCode => $this->primaryKey,
				]])->fetchObject();
				
				if (! $this->entity)
				{
					$this->primaryKey = 0;
					$this->entity = $this->entityClass->createObject();
				}
			}
			else
			{
				$this->entity = $this->entityClass->createObject();
			}
		}
		
		return $this->entity;
	}
	
	protected function executeForm()
	{
		if (! $this->request->isPost())
			return false;
			
		if (! check_bitrix_sessid())
		{
			$GLOBALS['APPLICATION']->AuthForm(Loc::getMessage("ACCESS_DENIED"));
		}
		
		$this->fillSavingEntity();
		
		$entity = $this->getEntity();
		$obResult = $entity->save();
		
		$this->primaryKey = $entity->get($this->primaryCode);
		
		if ($obResult->isSuccess())
		{
			$cacheTag = $this->entityClass->getCacheTag();
			
			if (! empty($cacheTag))
			{
				$taggedCache = Application::getInstance()->getTaggedCache();
				$taggedCache->clearByTag($cacheTag);
			}
			
			if ($this->request->getPost('apply'))
				$this->goToForm([$this->primaryCode => $this->primaryKey]);
			else
				$this->goToList();
		}
		else
		{
			foreach($obResult->getErrors() as $error)
			{
				$this->addMessage($error->getMessage(), self::MESS_ERROR);
			}
		}
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
	protected function addMessage($message, $type = '')
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
	
	protected function fillSavingEntity()
	{
		$avaibledFields = $this->entityClass->getEntity()->getFields();
		
		$entity = $this->getEntity();
		foreach ($avaibledFields as $field)
		{
			$name = $field->getName();
			$value = $this->request->getPost($name);
			$editable = ($field->getParameter('bxmod_readonly') !== true and $field->getParameter('bxmod_hidden') !== true);
			if (! $editable or $value === null)
				continue;
			
			switch ($field->getTypeMask())
			{
				case FieldTypeMask::SCALAR:
					switch ($field->getDataType())
					{
						case 'boolean':
							$value = ($value === 'Y' ? true : false);
							break;
						
						case 'date':
							$value = Date::createFromTimestamp(strtotime($value));
							break;
					}
					
					$entity->set($field->getName(), $value);
					break;
				
				case FieldTypeMask::ONE_TO_MANY:
				case FieldTypeMask::MANY_TO_MANY:
					if (! is_array($value))
						$value = [$value];
					
					if ($this->primaryKey) $entity->removeAll($name);
					foreach ($value as $ID)
					{
						$entity->addTo($name, ($field->getRefEntityName() . 'Table')::wakeUpObject($ID));
					}
					break;
			}
		}
	}
	
	public function checkPermissions($moduleName, $needsPerm)
	{
		global $APPLICATION;
		
		if ($APPLICATION->GetGroupRight($moduleName) < $needsPerm)
			$APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
	}
	
	/*
	 * @param array $data - data with keys: TEXT, LINK, ICON[btn_list|btn_delete]
	 */
	public function addTopMenuItem($data)
	{
		if (isset($data['TITLE']))
			$data['TEXT'] = $data['TITLE'];
		
		$this->topMenu[] = $data;
	}
	
	public function getTopMenuItems()
	{
		return $this->topMenu;
	}
	
	public function addTab($tab)
	{
		$this->tabs[] = $tab;
	}
	
	public function getTabs()
	{
		if (count($this->tabs))
		{
			return $this->tabs;
		}
		
		return [[
			"TAB" => '-',
		]];
	}
	
}