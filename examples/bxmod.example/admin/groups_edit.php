<?php

$moduleName = 'bxmod.example';

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \MashinaMashina\Bxmod\Admin\Form\Generator;
use \Bxmod\Example\StudentsGroupTable;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

Loader::includeModule($moduleName);
Loc::loadMessages(__FILE__);

$generator = new Generator(new StudentsGroupTable);
$generator->checkPermissions($moduleName, 'W');
$generator->init('bxmod_groups_edit.php', 'bxmod_groups_list.php');
$generator->setLangMessage([
	'entity' => Loc::getMessage('BXMOD_GROUP'),
	'entity_add' => Loc::getMessage('BXMOD_GROUP_ADD'),
	'entity_edit' => Loc::getMessage('BXMOD_GROUP_EDIT'),
	'entity_delete' => Loc::getMessage('BXMOD_GROUP_DELETE'),
	'entity_list' => Loc::getMessage('BXMOD_GROUP_LIST'),
]);
$generator->generate();

$APPLICATION->SetTitle($generator->getPrimaryKey() ? Loc::getMessage('BXMOD_GROUP_EDITING') : Loc::getMessage('BXMOD_GROUP_CREATING'));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

echo $generator->display();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");