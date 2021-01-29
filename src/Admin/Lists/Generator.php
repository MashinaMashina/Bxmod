<?php
namespace MashinaMashina\Bxmod\Admin\Lists;

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\UI\AdminPageNavigation;
use \MashinaMashina\Bxmod\Admin\BaseGenerator;

class Generator extends BaseGenerator
{
	protected $adminList;
	
	public function generate()
	{
		$tableID = $this->entityClass->getTableName();
		$sort = new \CAdminSorting($tableID, $this->primaryCode, 'asc');
		$this->adminList = new \CAdminList($tableID, $sort);
		
		$this->executeGroupActions();
		
		$this->buildTableHeader();
		
		$nav = new AdminPageNavigation($tableID);
		
		$sortBy = $this->request->getQuery('by');
		$sortOrder = $this->request->getQuery('order');
		
		$listData = [
			'count_total' => true,
			'offset' => $nav->getOffset(),
			'limit' => $nav->getLimit(),
		];
		
		$listData['order'] = [$this->primaryCode => 'asc'];
		if ($sortBy and $sortOrder)
		{
			$listData['order'] = [$sortBy => $sortOrder];
		}
		
		$entitiesList = $this->entityClass->getList($listData);

		$nav->setRecordCount($entitiesList->getCount());
		
		$this->adminList->setNavigation($nav, Loc::getMessage('PAGES')); // TODO
		
		while ($entity = $entitiesList->fetchObject())
		{
			$this->buildTableLine($entity);
		}
		
		$aContext = [[
			'TEXT' => $this->getLangMessage('entity_add'),
			'LINK' => $this->formLink(),
			'TITLE' => $this->getLangMessage('entity_add'),
			'ICON' => 'btn_new',
		]];

		// и прикрепим его к списку
		$this->adminList->AddAdminContextMenu($aContext);
		
		$this->adminList->AddFooter([
			['title' => Loc::GetMessage('MAIN_ADMIN_LIST_SELECTED'), 'value' =>  $entitiesList->getCount()],
			['counter' => true, 'title' => Loc::GetMessage('MAIN_ADMIN_LIST_CHECKED'), 'value' => '0'],
		]);
		
		$this->adminList->AddGroupActionTable([
			'delete' => Loc::GetMessage('MAIN_ADMIN_LIST_DELETE'),
			'activate' => Loc::GetMessage('MAIN_ADMIN_LIST_ACTIVATE'),
			'deactivate' => Loc::GetMessage('MAIN_ADMIN_LIST_DEACTIVATE'),
		]);
		
		$this->adminList->CheckListMode();
	}
	
	public function display()
	{
		$this->adminList->DisplayList();
	}
	
	protected function executeGroupActions()
	{
		$action = $this->request->getPost('action');
		$target = $this->request->getPost('action_target');
		
		if (empty($action))
			$action = $this->request->getPost('action_button');
		
		$arIds = $this->adminList->GroupAction();
		
		if ($arIds === false and $target !== 'selected')
		{
			return;
		}
		
		$filter = [];
		if ($target !== 'selected')
			$filter[$this->primaryCode] = $arIds;
		
		$collection = $this->entityClass::getList([
			'select' => [$this->primaryCode],
			'filter' => $filter,
		])->fetchCollection();
		
		switch ($action)
		{
			case 'delete':
				foreach ($collection as $entity)
					$entity->delete();
				
				return;
				break;
			
			case 'activate':
			case 'deactivate':
				$active = $action === 'activate';
				
				foreach ($collection as $entity)
				{
					$entity->set('ACTIVE', $active);
				}
				break;
		}
		
		$obResult = $collection->save();
		
		if(! $obResult->isSuccess())
		{
			$this->adminList->AddGroupError($obResult->getMessage()); // TODO
		}
	}
	
	protected function buildTableHeader()
	{
		$headers = [];
		$fields = $this->entityClass->getEntity()->getFields();
		foreach ($fields as $field)
		{
			$headers[] = [
				'id' => $field->getName(),
				'content' => $field->getTitle(),
				'sort' => $field->getName(),
				'default' =>$field->getParameter('required') or $field->getName() === $this->primaryCode,
			];
		}

		$this->adminList->AddHeaders($headers);
	}
	
	protected function buildTableLine($entity)
	{
		$id = reset($entity->primary);
		$editLink = $this->formLink([$this->primaryCode => $id]);
		
		$row = $this->adminList->AddRow($id, $entity->collectValues()); 
		
		// сформируем контекстное меню
		$arActions = [];

		$arActions[] = [
			'ICON' => 'edit',
			'DEFAULT' => true,
			'TEXT' => $this->getLangMessage('entity_edit'),
			'ACTION' => $this->adminList->ActionRedirect($editLink)
		];
		
		$arActions[] = [
			'ICON' => 'delete',
			'TEXT' => $this->getLangMessage('entity_delete'),
			'ACTION' => 'if(confirm("'.$this->getLangMessage('entity_delete').'?")) '.$this->adminList->ActionDoGroup($id, 'delete')
		];

		// применим контекстное меню к строке
		$row->AddActions($arActions);
	}
}