## Удаление модуля в Битрикс с сохранением данных
Пример реализации есть [в тестовом модуле](https://github.com/MashinaMashina/Bxmod/blob/master/examples/bxmod.example/install/index.php)

Чтобы вывести пользователю форму запроса сохранения таблиц, удаление модуля требуется разделить на несколько шагов.

Код в методе DoUninstall():
```php
global $APPLICATION;

// если шаг не передан, или он меньше 2, то считаем что шаг 1
if($_REQUEST['step'] < 2)
{
	// Подключаем файл с формой удаления
	$APPLICATION->IncludeAdminFile('Удаление модуля', __DIR__ . '/unstep1.php');
	return;
}

// Далее логика шага 2

// Сохранять ли данные модуля
$saveData = $_REQUEST['savedata'] === 'Y';

// Удаляем файлы
DeleteDirFiles(__DIR__ . '/admin/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin');

// Если не сохранять данные, удаляем их
if (! $saveData)
{
	$this->UninstallEntities();
}

// Удаляем модуль
UnRegisterModule($this->MODULE_ID);
```

Код файла unstep1.php:
```php
<?if(!check_bitrix_sessid()) return;?>
<form action="<?= $APPLICATION->GetCurPage() ?>" method="get">
	<?= bitrix_sessid_post();?>
	<input type="hidden" name="lang" value="<?=LANG?>" />
	<input type="hidden" name="id" value="<?=htmlspecialcharsEx($_REQUEST['id'])?>" />
	<input type="hidden" name="uninstall" value="Y" />
	<input type="hidden" name="step" value="2" />
	<? CAdminMessage::ShowMessage('Вы можете сохранить данные модуля, сохранив таблицы') ?>
	<p><?=GetMessage('MOD_UNINST_SAVE')?></p>
	 <p><input type="checkbox" name="savedata" id="savedata" value="Y" checked="checked" /><label for="savedata"><?=GetMessage('MOD_UNINST_SAVE_TABLES')?></label><br /></p>
	<input type="submit" value="<?=GetMessage('MOD_UNINST_DEL');?>" />
</form>
```