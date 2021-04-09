<?php

namespace MashinaMashina\Bxmod\Orm\Fields;

use Bitrix\Main\Localization\Loc;

trait FieldTrait
{
	public $isbxmod = true;
	
	public function getEditorClass()
	{
		return str_replace(__NAMESPACE__, 'MashinaMashina\Bxmod\Admin\Form\Editors', get_called_class());
	}
	
	public function getFillerClass()
	{
		return str_replace(__NAMESPACE__, 'MashinaMashina\Bxmod\Admin\Form\Fillers', get_called_class());
	}
	
	public function getViewerClass()
	{
		return str_replace(__NAMESPACE__, 'MashinaMashina\Bxmod\Admin\Lists\Viewers', get_called_class());
	}
	
	/**
	 * Lang phrase
	 *
	 * @param $descr
	 *
	 * @return $this
	 */
	public function configureDescription($descr)
	{
		$this->setParameter('bxmod_description', $descr);
		return $this;
	}

	public function getDescription()
	{
		$descr = $this->getParameter('bxmod_description');
		
		if($descr !== null)
		{
			return $descr;
		}
		
		$langCode = $this->getLangCode() . '_DESCR';
		if(($descr = Loc::getMessage($langCode)) !== '')
		{
			$this->setParameter('bxmod_description', $descr);
			return $descr;
		}

		return '';
	}
}
