<?php
namespace MashinaMashina\Bxmod\Admin\Form;

use \Bitrix\Main;

class AjaxInput extends Main\Engine\Controller
{
	public function getSelectOptionsAction()
	{
		$query = Main\Application::getInstance()->getContext()->getRequest()->getPost('query');
		$params = $this->getUnsignedParameters();
		
		$tablet = ($params['entity'])::getEntity();
		$fields = $tablet->getFields();
		
		if (! isset($fields[$params['field_name']]))
		{
			$this->addError(new Main\Error('Incorrect field name', 'incorrect_field_name'));
			return null;
		}
		
		$references = $fields[$params['field_name']]->getAllReferences([
			'query' => $query,
		]);
		
		$result = [];
		foreach ($references as $id => $name)
		{
			$result[] = [
				'id' => $id,
				'name' => $name,
			];
		}
		
		return $result;
	}
}