<?php
namespace MashinaMashina\Bxmod\Tools;

class AssetsManager
{
	public static function init()
	{
		$dir = static::getBrowseDir();
		
		$arJsConfig = [
			'chosen' => [ 
				'js' => $dir .'/assets/chosen/chosen.jquery.min.js', 
				'css' => $dir .'/assets/chosen/chosen.min.css', 
				'rel' => ['jquery2'], 
			],
			'autocomplete' => [ 
				'js' => $dir .'/assets/autocomplete/jquery-ui.min.js', 
				'css' => $dir .'/assets/autocomplete/jquery-ui.min.css', 
				'rel' => ['jquery2'], 
			],
			'bxmod_admin_form' => [ 
				'js' => $dir .'/assets/bxmod/script.js',
				'css' => $dir .'/assets/bxmod/style.css', 				
				'rel' => ['jquery2'], 
			],
		]; 

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