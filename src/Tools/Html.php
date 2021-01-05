<?php
namespace MashinaMashina\Bxmod\Tools;

class Html
{
	public static function buildSimpleTag($tag, $data)
	{
		return static::buildTag($tag, $data, '', true);
	}
	
	public static function buildTag($tag, $data, $content = '', $isSimple = false)
	{
		$str = "<{$tag} ";
		foreach ($data as $k => $v)
		{
			if (empty($k))
				continue;
			
			$str .= $k . '="' . htmlspecialcharsbx($v) . '" ';
		}
		$str .= '>';
		
		$str .= $content;
		
		if (! $isSimple)
			$str .= "</{$tag}>";
		
		return $str;
	}
}