<?php
namespace MashinaMashina\Bxmod\Tools;

class AssetsManager
{
	public static function init()
	{
		$dir = static::getBrowseDir();
		
		$arJsConfig = array(
			'chosen' => array( 
				'js' => $dir .'/assets/lib/chosen/chosen.jquery.min.js', 
				'css' => $dir .'/assets/lib/chosen/chosen.min.css', 
				'rel' => array('jquery2'), 
			),
			'autocomplete' => array( 
				'js' => $dir .'/assets/lib/autocomplete/jquery-ui.min.js', 
				'css' => $dir .'/assets/lib/autocomplete/jquery-ui.min.css', 
				'rel' => array('jquery2'), 
			),
		); 

		foreach ($arJsConfig as $ext => $arExt) {
			\CJSCore::RegisterExt($ext, $arExt); 
		}
	}
	
	public static function getBrowseDir()
	{
		static $dir;
		
		if (! isset($dir))
		{
			$dir = realpath(__DIR__ . '/../');
			$dir = str_replace('\\', '/', $dir);
			$dir = str_replace($_SERVER['DOCUMENT_ROOT'], '', $dir);
		}
		
		return $dir;
	}
}