<?php

$moduleName = 'bxmod.example';

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \MashinaMashina\Bxmod\Admin\Lists\Generator;
use \Bxmod\Example\StudentsTable;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

Loader::includeModule($moduleName);
Loc::loadMessages(__FILE__);

$generator = new Generator(new StudentsTable);
$generator->checkPermissions($moduleName, 'W');
$generator->init('bxmod_students_edit.php', 'bxmod_students_list.php');
$generator->setLangMessage([
	'entity' => Loc::getMessage('BXMOD_STUDENT'),
	'entity_add' => Loc::getMessage('BXMOD_STUDENT_ADD'),
	'entity_edit' => Loc::getMessage('BXMOD_STUDENT_EDIT'),
	'entity_delete' => Loc::getMessage('BXMOD_STUDENT_DELETE'),
	'entity_list' => Loc::getMessage('BXMOD_STUDENT_LIST'),
]);
$generator->generate();

$APPLICATION->SetTitle(Loc::getMessage('BXMOD_STUDENT_LIST'));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

echo $generator->display();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");