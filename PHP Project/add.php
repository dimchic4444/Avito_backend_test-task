<?php
/*
 Список получаемых параметров: POST
head - заголовок объявления
text - описание объявление
images - ссылки на фото
price - цена
 Коды ответа:
1 - Все ок
2 - ошибка подключения MYSQL
3 - Не пройдена валидация полей
4 - Ошибка выполнения SQL запроса
 */
require_once 'parameters_of_database.php';
require_once 'functions_for_add_ads.php';
$answCode = 1; //По умолчанию код ответа - 1, что значит что все хорошо
$adID = -1; // По умолчанию ID объявления не известен
//$link == false
if ($link->connect_errno){
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
    $answCode = 2;
} else {
    if ((validateFields($_POST["head"],$_POST["text"],$_POST["images"]) == "yes") && (antiSQLInjectionValidate($_POST["head"],$_POST["text"],$_POST["images"],$_POST["price"]) == "yes")) {
        $idForNewAd = getUniqueID($link);
        $headForNewAd = $_POST["head"];
        $textForNewAd = $_POST["text"];
        $imagesForNewAd = $_POST["images"];
        $priceForNewAd = (int)$_POST["price"];
        if (addAd($link,$idForNewAd,$headForNewAd,$textForNewAd,$imagesForNewAd,$priceForNewAd) == "yes")
        {
            $answCode = 1;
            $adID = $idForNewAd;
        } else {
            $answCode = 4;
        }
    } else {
        $answCode = 3;
    }
}
        $answ = array('code'=> $answCode, 'ID'=>$adID);
        echo json_encode($answ, JSON_UNESCAPED_UNICODE); //возращаем JSON
