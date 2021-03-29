## Миграции в Bxmod
Пример реализации есть [в тестовом модуле](https://github.com/MashinaMashina/Bxmod/blob/master/examples/bxmod.example/install/index.php)

Основа логики:
1. Первую установку модуля производим как обычно, но сразу в параметрах устанавливаем версию модуля.
2. При удалении модуля, если пользователь выбрал сохранение таблиц, так же сохраняем версию модуля (достаточно просто не удалять).
3. Заменяем файлы модуля на новую версию.
4. При установке модуля проверяем сохраненный ранее параметр версии модуля, если такой параметр есть - значит нужна миграция.
5. Запускаем все файлы миграций между старой версией модуля и актуальной версией. Файлы хранятся в папке /install/migrations/. Имя файла - код\_версии\_модуля.php. Например 1.0.2.php запустится если старая версия модуля 1.0.1 или ниже, а новая 1.0.2 или выше.

Код в методе DoInstall():
```php
public function DoInstall()
{
	 // Регистрируем модуль
	RegisterModule($this->MODULE_ID);
	
	// Проверяем, был ли этот модуль когда-то уже установлен
	$oldVersion = \Bitrix\Main\Config\Option::get($this->MODULE_ID, 'INSTALLED_VERSION');
	if ($oldVersion)
	{
		// Если был, то проводим миграцию. Нужны миграции только выше прошлой версии
		// Первый параметр - папка с миграциями (слеш на конце - обязателен)
		// Второй параметр - до какой версии пропускать миграции
		$this->Migrate(__DIR__ . '/migrations/', $oldVersion);
	}
	else
	{
		// Если не был, устанавливаем сущности как обычно
		$this->InstallEntities();
	}
	// После установки данных в базу - продолжаем как обычно
	$this->InstallEvents();
	...
	
	// Сохраняем версию установленного модуля
	\Bitrix\Main\Config\Option::set($this->MODULE_ID, 'INSTALLED_VERSION', $this->MODULE_VERSION);
}
```

Код в методе DoUnstall():
```php
public function DoUninstall()
{
	...
	
	// Удялем версию и данные из БД, если пользователь выбрал не сохранять таблицы
	if (! $saveData)
	{
		$this->UninstallEntities();
		\Bitrix\Main\Config\Option::delete($this->MODULE_ID, ['name' => 'INSTALLED_VERSION']);
	}
	
	// Удаляем модуль
	UnRegisterModule($this->MODULE_ID);
}
```
Информация о том, как запросить у пользователя сохранение данных доступна [по ссылке](uninstall_with_save_data.md)