<?php
namespace MashinaMashina\Bxmod\Admin;

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Type\Date;
use \Bitrix\Main\Application;
use \Bitrix\Main\ORM\Fields\FieldTypeMask;
use \MashinaMashina\Bxmod\Tools\Html;
use \MashinaMashina\Bxmod\Orm\Entity\DataManager;

Loc::loadMessages(__FILE__);

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
	
	public function __construct(DataManager $entityClassname)
	{
		$this->entityClass = $entityClassname;
		$this->request = Application::getInstance()->getContext()->getRequest();
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
		
		$avaibledFields = ($this->entityClass)::getEntity()->getFields();
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
		$required = $field->getParameter('required');
		$fieldName = htmlspecialcharsbx($field->getTitle());
		
		if ($field->getParameter('required'))
		{
			$fieldName = '<b>' . $fieldName . '</b>'; 
		}
		
		echo '<tr><td width="40%">';
		echo $fieldName;
		echo '</td><td width="60%">';
		echo $this->buildInput($field);
		echo '</td></tr>';
	}
	
	protected function buildInput($field)
	{
		switch ($field->getTypeMask())
		{
			case FieldTypeMask::SCALAR:
				return $this->buildScalarInput($field);
			
			case FieldTypeMask::MANY_TO_MANY:
				return $this->buildRelationInput($field);
		}
	}
	
	protected function buildRelationInput($field)
	{
		$autocompleteLink = $field->getParameter('bxmod_input_ajax_autocomplete_link');
		
		if (empty($autocompleteLink))
		{
			// TODO. getList + generate select
		}
		else
		{
			if (strpos($autocompleteLink, '?') === false)
				$autocompleteLink .= '?';
			else
				$autocompleteLink .= '&';
			
			$autocompleteLink .= bitrix_sessid_get() . '&query=';
			
			$options = '';
			$selected = $this->getEntity()->get($field->getName());
			foreach ($selected as $select)
			{
				$options .= Html::buildTag('option', [
					'value' => $select['id'],
					'selected' => '',
				], htmlentities($select['name']));
			}
			
			\CJSCore::Init(['chosen', 'autocomplete']);
			
			$uniqid = 'id'.uniqid();
			
			$data = ['id' => $uniqid, 'multiple' => '', 'name' => $field->getName() . '[]'];
			$result = Html::buildTag('select', $data, $options . '<option value="" disabled>Enter name...</option>');
			
			$result .= '<script>
				$(function(){
					$("#'.$uniqid.'").chosen({
						width:"300px",
					});
					
					$(".chosen-choices input").autocomplete({
						minLength: 2,
						delay: 500,
						source: function( request, response ) {
							$.ajax({
								url: "'.$autocompleteLink.'"+request.term,
								dataType: "json",
							}).done(function(data) {
									$("#'.$uniqid.' option").each(function(){
										if (! $(this).prop("selected"))
											$(this).remove();
									});
									data.reverse();
									response( $.map( data, function( item ) {
										$("#'.$uniqid.'").prepend(\'<option value="\'+item.id+\'">\' + item.name + \'</option>\');
									}));
									$("#'.$uniqid.'").trigger("chosen:updated");
									$(".chosen-choices input").val(request.term);
								});
						}
					});
				});
			</script>';
		}
		
		return $result;
	}
	
	protected function buildScalarInput($field)
	{
		$entity = $this->getEntity();
		
		$printName = htmlspecialcharsbx($field->getName());
		$printValue = htmlspecialcharsbx($entity[$field->getName()]);
		
		if ($field->getParameter('bxmod_readonly'))
		{
			return $printValue;
		}
		
		$tagData = [
			'name' => $field->getName(),
			'value' => $entity[$field->getName()],
		];
		
		switch($field->getDataType())
		{
			case 'integer':
			case 'float':
			case 'string':
				return Html::buildSimpleTag('input', $tagData + [
					'type' => 'text',
				]);
			
			case 'date':
				\CJSCore::Init(array("jquery","date"));
				return Html::buildSimpleTag('input', $tagData + [
					'type' => 'text',
					'onclick' => 'BX.calendar({node: this, field: this, bTime: false})',
				]);
			
			case 'enum':
				$options = '';
				foreach ($field->getParameter('values') as $value)
				{
					$selected = ($value === $tagData['value'] ? 'selected' : '');
					
					$options .= Html::buildTag('option', [
						'value' => $value,
						$selected => '',
					], $value);
				}
				
				return Html::buildTag('select', [
					'name' => $field->getName(),
				], $options);
			
			case 'boolean':
				$checked = ($tagData['value'] ? 'checked' : '');
			
				$str = Html::buildSimpleTag('input', [
					'type' => 'hidden',
					'value' => 'N',
				] + $tagData);
				
				return $str . Html::buildSimpleTag('input', [
					'type' => 'checkbox',
					'value' => 'Y',
					$checked => '',
				] + $tagData);
			
			default:
				return 'not supported type: ' . $field->getDataType();
				break;
		}
	}
	
	protected function getEntity()
	{
		if (! isset($this->entity))
		{
			if ($this->primaryKey > 0)
			{
				$this->entity = ($this->entityClass)::getList(['filter' => [
					$this->primaryCode => $this->primaryKey,
				]])->fetchObject();
				
				if (! $this->entity)
				{
					$this->primaryKey = 0;
					$this->entity = ($this->entityClass)::createObject();
				}
			}
			else
			{
				$this->entity = ($this->entityClass)::createObject();
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
			$cacheTag = ($this->entityClass)::getCacheTag();
			
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
		$avaibledFields = ($this->entityClass)::getEntity()->getFields();
		
		$entity = $this->getEntity();
		foreach ($avaibledFields as $field)
		{
			$name = $field->getName();
			$value = $this->request->getPost($name);
			if ($field->getParameter('bxmod_readonly') or $value === null)
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
				"TAB" => 'Tab',
				"TITLE"=> 'Tab title',
		]];
	}
	
}