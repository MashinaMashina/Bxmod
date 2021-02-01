## Урок 1. С чего начинается модуль?

Правильно, с установщика.
Класс установщик теперь должен наследоваться не от CModule, а от \MashinaMashina\Bxmod\Install\BaseInstaller:

    <?php
    
    use \MashinaMashina\Bxmod\Install\BaseInstaller;
    
    class bxmod_example extends BaseInstaller
    {
    	public $MODULE_ID = 'bxmod.example';
    	public $MODULE_NAME;
    	...
В методе __construct() надо указать нужные таблеты с помощью вызова $this->addModuleEntity(string $tabletClass). Пока модуль не установлен, автозагрузка его классов не работает, загрузим вручную:

    public function __construct()
    {
    	...
    	require_once __DIR__ . '/../lib/students.php';
    	$this->addModuleEntity(\Bxmod\Example\StudentsTable::class);
    	...
    }

Можно добавить событие, которое должен зарегистрировать Битрикс с помощью вызова $this->addModuleEvent(string $fromModuleName, string $fromModuleEvent, string $toModule, string $toClass, string $toFunction):
События будут автоматически устанавливаться у удаляться при установке и удалении модуля соответственно.

Осталось добавить $this->InstallEntities() и $this->InstallEvents() в метод DoInstall()
а так же $this->UninstallEvents() и $this->UninstallEntities() в DoUninstall метод.

[Полная правильная реализация установщика для примера в тестовом модуле](https://github.com/MashinaMashina/Bxmod/blob/master/examples/bxmod.example/install/index.php)

## [Следующий урок](lesson2.md)
