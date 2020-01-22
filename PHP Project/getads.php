<?php
/*
 Список получаемых параметров: GET
page - номер страницы (на странице 10 объявлений)
sort - priceup, pricedown, dateup, datedown (По умолчанию datedown)
 */
require_once 'functions_for_getting_ads.php';
require_once 'parameters_of_database.php';

if (ctype_digit($_GET["page"])) {
    $needPage = (int)$_GET["page"];
} else {
    $needPage = 1;
}
    $IDS = getIDS($needPage,$_GET["sort"],$link);
    $answ = array();
    for ($i=0;$i<count($IDS);$i++) {
        $oneAd = getAdByID($IDS[$i], $link);
        $answFields = array(
            'head' => $oneAd["head"],
            'image' => explode(" ", $oneAd["images"])[0],
            'price' => $oneAd["price"]
        );
        $answ += ["$IDS[$i]" => $answFields];
    }
    echo json_encode($answ, JSON_UNESCAPED_UNICODE); //возращаем JSON


