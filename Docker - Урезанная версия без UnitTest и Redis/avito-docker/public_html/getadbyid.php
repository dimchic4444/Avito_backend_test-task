<?php

/*
 Список получаемых параметров: GET
id - ID объявления
text - нужно ли добавить в ответ описание объявления
images - нужно ли добавить в ответ ссылки на все картинки
 */


require_once 'parameters_of_database.php';
require_once 'functions_for_getting_ads.php';


if (validateID($_GET["id"]) == "yes") {
        $adData = getAdByID($_GET["id"], $link);
        if ($adData != "no") {
            $answ = array('head' => $adData["head"], 'price' => $adData["price"]);
            if (count(explode(" ", $adData["images"])) > 0) {
                if ((isset($_GET["images"]))&&($_GET["images"] == "yes")) {
                    $images = explode(" ", $adData["images"]);
                    $imagesToJSON = array();
                    for ($i=0;$i<count($images);$i++) {
                        $imagesToJSON += ["$i" => $images[$i]];
                    }
                    $answ += ['images' => $imagesToJSON];
                } else {
                    $answ += ['image' => explode(" ", $adData["images"])[0]];
                }
            }
            if ((isset($_GET["text"]))&&($_GET["text"] == "yes")) {
                $answ += ['text' => $adData["text"]];
            }
            echo json_encode($answ, JSON_UNESCAPED_UNICODE); //возращаем JSON
        }
}






