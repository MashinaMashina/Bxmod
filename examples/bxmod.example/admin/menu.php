<?php

if ($APPLICATION->GetGroupRight("bxmod.example") !== 'W')
	return [];

use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$aMenu[] = array(
	'parent_menu' => 'global_menu_services',
	'sort' => 0,
	'text' => Loc::getMessage('BXMOD_STUDENTS_MENU'),
	'title' => Loc::getMessage('BXMOD_STUDENTS_MENU'),
	'url' => 'bxmod_students_list.php?lang='.LANGUAGE_ID,
	'more_url' => [
		'bxmod_students_list.php',
		'bxmod_students_edit.php',
	],
	'icon' => 'blog_menu_icon',
);

$aMenu[] = array(
	'parent_menu' => 'global_menu_services',
	'sort' => 0,
	'text' => Loc::getMessage('BXMOD_GROUPS_MENU'),
	'title' => Loc::getMessage('BXMOD_GROUPS_MENU'),
	'url' => 'bxmod_groups_list.php?lang='.LANGUAGE_ID,
	'more_url' => [
		'bxmod_groups_list.php',
		'bxmod_groups_edit.php',
	],
	'icon' => 'blog_menu_icon',
);

return $aMenu;