<?php

require __DIR__ . '\DB.php';

require __DIR__ . '\DBQuery.php';

$db = DB::connect('mysql:host=localhost;dbname=z_short_link', 'root', 'root');

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->getAttribute(PDO::ATTR_ERRMODE);
//$db->close();

$db->reconnect();
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$query = new DBQuery($db);

echo '<hr> queryAll <br>';
$sql = "SELECT * FROM users";
print_r($query->queryAll($sql));

/**
 *  Array
 * (
 * [0] => Array
 * (
 * [id] => 1
 * [email] => zotov_mv@groupbwt.com
 * [password] => $2y$10$0MTsYsnMVJ7iq6bTvIpUGOK8WVN1uErud
 * )
 *
 * [1] => Array
 * (
 * [id] => 2
 * [email] => admin@groupbwt.com
 * [password] => $2y$10$0MTsYsnMVJ7iq6bTvIpUGOK8WVN1uErud
 * )
 * )
 */

echo '<hr> queryRow <br>';
print_r($query->queryRow('SELECT * FROM users limit 1'));

/**
 * Array
 * (
 * [id] => 1
 * [email] => zotov_mv@groupbwt.com
 * [password] => $2y$10$0MTsYsnMVJ7iq6bTvIpUGOK8WVN1uErud
 * )
 */

echo '<hr> queryColumn <br>';
print_r($query->queryColumn('SELECT email FROM users'));
/**
 * Array
 * (
 * [0] => zotov_mv+24787@groupbwt.com
 * [1] => zotov_mv+47748@groupbwt.com
 * [2] => zotov_mv@groupbwt.com
 * )
 */

echo '<hr> queryScalar <br>';
echo $query->queryScalar('SELECT email FROM users');

/**
 * admin@groupbwt.com
 */

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

/**
 * Array
 * (
 * [id] => 20
 * [email] => zotov_mv+70773@groupbwt.com
 * [password] => $2y$10$m7ai3oLBxbF4akWMLXEDteF.0zbv6deN0
 * )
 */

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