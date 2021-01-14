# Creating-a-directory

<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <image src="https://media-exp1.licdn.com/dms/image/C4D1BAQELs-IvRTaMrA/company-background_10000/0/1550134278426?e=1610676000&v=beta&t=IO0cIzHTonUHWEFWpuddt3L_Z3DBW_cmQvXyLUciofU" height="100px">
    </a>
    <h1 align="center">Тестовое задание №2</h1>
    <br>
</p>

ОПИСАНИЕ
-------------------
WEB-программист (PHP)
Задание - Создание справочника
Создать справочник журналов, с возможностью CRUD. (Можно использовать любой Framework или голый php)
1. У каждого журнала должны быть:
Название. (Обязательное поле)
Короткое описание. (Необязательное поле)
Картинка. (jpg или png, не больше 2 Мб, должна сохраняться в отдельную папку и иметь уникальное имя файла)
Авторы (Обязательное поле, может быть несколько авторов у одного журнала, должна быть возможность выбирать из списка авторов, который создается отдельно).
Дата выпуска журнала.
2. Список авторов создается отдельно. Также должна быть возможность добавления, удаления и редактирования. У каждого автора должны быть:
Фамилия (Обязательное поле, не короче 3 символов)
Имя (Обязательное, не пустое)
Отчество (Необязательное)
3. На выходе получаем:
Просмотр отдельно страниц журналов и авторов. На странице авторов:
Должна быть возможность увидеть список всех журналов определенного автора.
Сделать сортировку авторов по фамилии

REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 5.6.0.

CONFIGURATION AND DATABASE
--------------------------
### Database

Edit the file `config/db.php` with real data, for example:
```

define('DB_SERVER' ,'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'mysql');
define('DB_NAME','directory');

```
Database name : directory

```
CREATE DATABASE directory;
```
Creating DataBase tables

Tables :

```

CREATE TABLE journals (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(100) NOT NULL,
  description VARCHAR(255),
  image VARCHAR(100) NOT NULL,
  release_date DATE
)


authors
CREATE TABLE authros (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  first_name VARCHAR(255) NOT NULL,
  middle_name VARCHAR(255),
  last_name VARCHAR(255) NOT NULL
)

```
