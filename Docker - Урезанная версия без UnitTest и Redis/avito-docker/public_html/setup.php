<?php
$link = new mysqli("mysql", "root", "rootpassword");
$link->set_charset("utf8");
    if ($link->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        echo "ошибка подключения";
    }
    $sql  = 'CREATE DATABASE avito_ads CHARACTER SET utf8 COLLATE utf8_unicode_ci';
$result = $link->query($sql);
if ($result == false) {echo "ошибка";} else {echo "все ок ";}
    $sql = 'use avito_ads';
$result = $link->query($sql);
if ($result == false) {echo "ошибка";} else {echo "все ок ";}
$sql = 'CREATE TABLE ads (id INT(11) UNSIGNED PRIMARY KEY, header VARCHAR(200), text VARCHAR(1000), images VARCHAR(1000), price INT(11), date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)';
$result = $link->query($sql);
if ($result == false) {echo "ошибка";} else {echo "все ок";}
