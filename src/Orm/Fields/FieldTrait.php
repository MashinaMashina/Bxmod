<?php

namespace MashinaMashina\Bxmod\Orm\Fields;

trait FieldTrait
{
	
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
		
	}
	
	
}
