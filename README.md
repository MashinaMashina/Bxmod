# Bxmod - база для модулей Битрикс

Сейчас расскажу тебе как сделать штуку для хранения данных в Битрикс гибче и легче, чем инфоблоки. С такой же простотой создания, как инфоблоки, где ты на 100% управляешь своей сущностью.

 - Создать вебформы? Легко!
 - Создать свой список регионов? Сколько угодно!
 - Просто новости хранить? Да пожалуйста!

## Описание

В Битрикс есть функционал создания своих сущностей для хранения данных ([ссылка](https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=4803&LESSON_PATH=3913.3516.5748.4803)). Да, из кода им удобно пользоваться. Но если дело доходит до удобного редактирования данных пользователями - тут уже начинаются проблемы. Каждый раз, на каждую сущность, на каждое поле приходится тратить огромное количество драгоценного времени. Bxmod - решает эту проблему.

Bxmod - база для модулей Битрикс использующих свои ORM сущности.
Создает на основе описания полей в таблете список элементов, формы создания и редактирования элементов, функционал быстрого редактирования, удаления.
Добавляет в установщик модулей функционал создания таблиц в базе данных для таблетов.

> **Что такое таблет?**
> Это PHP класс с описанием ORM сущности в Битрикс. Он имеет суффикс Table, например, ProductTable.

## Первое знакомство
Установить Bxmod для знакомства проще всего через composer и показать битриксу ссылку на модуль из папки композера.

> Если вы еще не использовали composer, вначале стоит прописать в корне
> сайта `composer init` и следуя инструкции в консоли создать проект. В
> Битрикс необходимо подключить 1 файл: vendor/autoload.php в
> битриксовом init.php

После подготовки composer в корне сайта вводим команду:

    composer require mashinamashina/bxmod

 Покажем битриксу наш тестовый модуль:
 

    cd bitrix/modules
    ln -s ../../vendor/mashinamashina/bxmod/examples/bxmod.example bxmod.example
   
   Осталось установить модуль в панели администратора Битрикс в разделе Marketplace -> Установленные решения.
   На этом подготовку закончили, в разделе Сервисы у вас появились пункты `Bxmod. Список студентов` и `Bxmod. Список групп`
   Можно создавать сущности, удалять и делать с ними что захочется.

## Для взрослых
Начнем использовать на практике?
[Интеграция Bxmod в свой модуль](https://github.com/MashinaMashina/Bxmod/blob/master/docs/integrate/lesson1.md)