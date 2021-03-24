#### Урок 1.

##  Создание структуры модуля
Предположим, нам надо сделать модуль для управления студентами. У каждого студента есть поля: имя, фамилия, дата рождения, пол, описание, фотография. Так же для удобства добавим поле активности и ID.

Для начала создадим ORM таблет. Про ORM в Битрикс можно почитать по [ссылке](https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&CHAPTER_ID=05748&LESSON_PATH=3913.3516.5748)

Файл lib/students.php в папке модуля
Его содержимое:
```php
<?php

// Пространство имен модуля
namespace Bxmod\Example;

// Откуда брать Дата менеджер
use \MashinaMashina\Bxmod\Orm\Entity\DataManager;
// Откуда брать типы полей (для метода getMap())
use \MashinaMashina\Bxmod\Orm\Fields;

// Таблет наследуется от Дата менеджера в Bxmod
class StudentsTable extends DataManager
{
	public static function getTableName()
	{
		// Таблица, где будут храниться записи
		return 'bxmod_students';
	}
	
	public static function getMap()
	{
		// Пока нет никаких полей
		return [];
	}
}
```

Таблет есть, теперь укажем нужные поля в методе getMap(). Обратите внимание, что все типы полей загружаются из \MashinaMashina\Bxmod\Orm\Fields, это указано в коде выше.
```php
return [
	// Поле ID
	new Fields\IntegerField('ID', [
		'primary' => true, // Уникальное
		'autocomplete' => true, // Автозаполняемое
		'bxmod_readonly' => true, // Не редактируется пользователем
	]),
	// Активность студента
	new Fields\BooleanField('ACTIVE', [
		'default_value' => 1, // По-умолчанию - Да
		'bxmod_index' => true, // Необходим индекс в базе данных, для ускорения выборок с фильтром по полю
	]),
	// Имя
	new Fields\StringField('FIRST_NAME', [
		'required' => true, // Обязательно к заполнению
	]),
	// Фамилия
	new Fields\StringField('LAST_NAME', [
		'required' => true, // Обязательно к заполнению
	]),
	// Дата рождения
	new Fields\DateField('BIRTHDAY', [
	]),
	// Пол
	new Fields\EnumField('SEX', [
		'values' => ['M', 'F'], // Возможные значения M и F
	]),
	// Описание
	new Fields\TextField('DESCRIPTION', [
	]),
	// Изображение
	new Fields\FileField('AVATAR', [
	]),
];
```
[Все доступные типы полей](../all_fields.md)


## [Следующий урок](lesson2.md)
