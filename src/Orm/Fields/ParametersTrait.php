<?php
/*
 * Функционал указания параметров появился
 * относительно недавно.
 * Добавим функционал в более старые битриксы.
 */

namespace MashinaMashina\Bxmod\Orm\Fields;

trait ParametersTrait
{
	
	public function setParameter($name, $value)
	{
		if (method_exists(get_parent_class($this), 'setParameter'))
		{
			return parent::setParameter($name, $value);
		}
		
		$this->initialParameters[$name] = $value;

		return $this;
	}

	public function getParameter($name)
	{
		if (method_exists(get_parent_class($this), 'getParameter'))
		{
			return parent::getParameter($name);
		}
		
		return $this->initialParameters[$name];
	}

	public function hasParameter($name)
	{
		if (method_exists(get_parent_class($this), 'hasParameter'))
		{
			return parent::hasParameter($name);
		}
		
		return array_key_exists($name, $this->initialParameters);
	}
}