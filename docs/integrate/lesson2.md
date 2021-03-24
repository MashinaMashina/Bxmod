#### Урок 2.

## Установщик модуля

Класс установщик теперь должен наследоваться не от CModule, а от \MashinaMashina\Bxmod\Install\BaseInstaller:
```php
<?php

use \MashinaMashina\Bxmod\Install\BaseInstaller;
    
class bxmod_example extends BaseInstaller
{
	public $MODULE_ID = 'bxmod.example';
	public $MODULE_NAME;
	...
  ```
В методе __construct() надо указать нужные таблеты с помощью вызова $this->addModuleEntity(string $tabletClass). Пока модуль не установлен, автозагрузка его классов не работает, загрузим вручную:
```php
public function __construct()
{
	...
	require_once __DIR__ . '/../lib/students.php';
	$this->addModuleEntity(\Bxmod\Example\StudentsTable::class);
	...
}
```

Можно добавить событие, которое должен зарегистрировать Битрикс с помощью вызова $this->addModuleEvent(string $fromModuleName, string $fromModuleEvent, string $toModule, string $toClass, string $toFunction):
События будут автоматически устанавливаться и удаляться при установке и удалении модуля соответственно.

Осталось добавить $this->InstallEntities() и $this->InstallEvents() в метод DoInstall()
а так же $this->UninstallEvents() и $this->UninstallEntities() в DoUninstall метод.

В остальном реализация не отличается от типового модуля описанного в документации [https://dev.1c-bitrix.ru/learning/course/...](https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=3475&LESSON_PATH=3913.3435.4609.3475)

[Полная правильная реализация установщика для примера в тестовом модуле](https://github.com/MashinaMashina/Bxmod/blob/master/examples/bxmod.example/install/index.php)

## [Следующий урок](lesson2.md)
