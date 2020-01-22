<?php
//$link = mysqli_connect();
$link = new mysqli("localhost", "phpmyadmin", "12345", "avito_ads");
$link->set_charset("utf8");