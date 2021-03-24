####  Урок 3.

## Создание страниц в панели администратора
После создания ORM сущностей, ими надо как-то управлять, надо иметь возможность их просматривать.

### Страница списка элементов
Есть, например, таблет, который описывает ORM сущность студентов (Bxmod\Example\StudentsTable).
В папке модуля создадим файл /admin/students_list.php
Его контент:
```php
<?php
// Текущий модуль
$moduleName = 'bxmod.example';

// Укажем откуда брать нужные классы
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \MashinaMashina\Bxmod\Admin\Lists\Generator;
use \Bxmod\Example\StudentsTable;

// Подключим Битрикс
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

// Загрузим нужный модуль и языковые файлы
Loader::includeModule($moduleName);
Loc::loadMessages(__FILE__);

// Создаем объекс генератора списков, указываем нужный таблет
$generator = new Generator(new StudentsTable);
// Проверяем права
$generator->checkPermissions($moduleName, 'W');
// Указываем пути на страницу редактирования и списка
$generator->init('bxmod_students_edit.php', 'bxmod_students_list.php');
// Указываем подписи для страницы
$generator->setLangMessage([
	'entity' => 'Студент',
	'entity_add' => 'Добавить студента',
	'entity_edit' => 'Редактировать студента',
	'entity_delete' => 'Удалить студента',
	'entity_list' => 'Список студентов',
]);
// Генерируем страницу
$generator->generate();

// Указываем название страницы
$APPLICATION->SetTitle('Список студентов');

// Подключаем шапку панели администратора
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
// отображаем страницу
echo $generator->display();
// Подключаем футер панели администратора
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
```

Все поля автоматически подтянутся из описания сущности в таблете.

### Страница редактирования элемента
Код практически идентичен странице списка, просто вместо генератора списков \MashinaMashina\Bxmod\Admin\Lists\Generator, надо использовать генератор форм \MashinaMashina\Bxmod\Admin\Form\Generator.
Еще в зависимости от того, это страница создания или редактирования элемента, сделаем разный заголовок страницы:
```php
$APPLICATION->SetTitle($generator->getPrimaryKey() ? 'Редактирование студента' : 'Создание студента');
```
Кнопки удаления появятся автоматически и сразу будут работать.
