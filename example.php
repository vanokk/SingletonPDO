<?php
require_once 'src/autoload.php';

$host = 'localhost';
$dbName = 'users';
$userName = 'root';
$userPassword = 'root';

$db = SingletonPDO\DB::connect('mysql:host='.$host.';dbname='.$dbName, $userName, $userPassword);

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->getAttribute(PDO::ATTR_ERRMODE);
////$db->close();


$query = new SingletonPDO\DBQuery($db);
//
echo '<hr> queryAll <br>';
$sql = "SELECT * FROM users";
print_r($query->queryAll($sql));


echo '<hr> queryRow <br>';
print_r($query->queryRow('SELECT * FROM users limit 1'));

echo '<hr> queryColumn <br>';
print_r($query->queryColumn('SELECT email FROM users'));


echo '<hr> queryScalar <br>';
echo $query->queryScalar('SELECT email FROM users');


$db->reconnect();

$data = [
    'email'    => 'zotov_mv+' . rand(1, 99999) . '@groupbwt.com',
    'password' => password_hash('qwerty' . time(), PASSWORD_DEFAULT)
];
echo '<hr> INSERT INTO `users` (`email`, `password`) VALUES (:email, :password) <br>';
$rowCount = $query->execute("INSERT INTO `users` (`email`, `password`) VALUES (:email, :password)", $data);
//
echo "\ncount inserts row -> " . $rowCount . "\n";

$lastId = $db->getLastInsertID();
echo '<hr> queryRow by last ID <br>';

print_r($query->queryRow('SELECT * FROM users where id = :id', ['id' => $lastId]));


$updateData = [
    'password' => password_hash('qwerty' . time(), PASSWORD_DEFAULT),
    'id'       => $lastId
];

echo '<hr> Update `users` SET password = :password where id = :id <br>';
$rowCountUpdate = $query->execute("Update `users` SET password = :password where id = :id", $updateData);
echo "\ncount update row -> " . $rowCountUpdate . "\n";

echo '<hr>';
$rowCountDelete = $query->execute("DELETE FROM `users` where id = :id", ['id' => $lastId]);

echo "\n count delete row -> " . $rowCountDelete . "\n";

echo '<hr>';
echo "\n last query execution time -> " . $query->getLastQueryTime() . "\n";