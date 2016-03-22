# Checklist Singleton PDO

## Installation

```sh
composer require "vanokk/singleton-pdo:1.1.0"
```

## Usage

For example create database and make dump from sql/users.sql. In example.php set value for host, DB name, user name and user password.

```php
<?php
require_once 'src/autoload.php';

$host = 'localhost';
$dbName = 'users';
$userName = 'root';
$userPassword = 'root';

$db = SingletonPDO\DB::connect('mysql:host='.$host.';dbname='.$dbName, $userName, $userPassword);

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$query = new SingletonPDO\DBQuery($db);

$sql = "SELECT * FROM users";
echo $query->queryAll($sql);
```

