<?php
/*
Функция выполняет запрос к базе, и возвращает yes или no в зависимости от того,
существует ли объявление с данным ID в базе или нет
*/
function checkUniqueID($uniqueID, $link) {
    $sql  = 'SELECT id FROM `ads` WHERE id='.$uniqueID;
    $result = $link->query($sql);
//        mysqli_query($link, $sql);
    if ($result == false) {
//        print("Произошла ошибка при выполнении запроса");
    } else {
        $row = $result->fetch_assoc();
//            mysqli_fetch_array($result);
        if ($row["id"]) {
            return "yes";
        } else {
            return "no";
        }
    }
}
/*
Функция выдает нам уникальный идентификатор объявления
*/
function getUniqueID($link) {
    while (true) {
        $randID = rand(11111, 99999); //Генерируем случайный айдишник нашего объявления для предотвращения атак перебором
//        echo "Проверяем ID ".$randID." на уникальность";
        if (checkUniqueID($randID, $link)=="no") {
            return $randID;
            break;
        }
    }
}
/*
Извиняюсь за громоздкий код(
Функция возвращает yes в случае если все поля отвечают требованиям валидации
и no если хотя бы одно из полей не прошло валидацию
*/
function validateFields($head, $text, $images) {
    if ((($head) && (strlen($head) <= 200)) && (($text) && (strlen($text) <= 1000)) && (($images) && (count(explode(" ", $images)) <= 3))) {
        return "yes";
    } else {
        return "no";
    }
}

/*
Функция возвращает yes если все параметры отвечают требованиям безопасности
sql запросов, и no в случае если хотя бы один из параметров содержит символ
который может использоваться для sql инъекции
 */
function antiSQLInjectionValidate($head, $text, $images, $price) {
    $returnValue = "yes"; //По умолчанию все параметры отвечают требованиям безопасности
    for ($i=0;$i<strlen($head);$i++) {
        if (($head{$i}=='"')||($head{$i}=="'")) {
            $returnValue = "no";
        }
    }
    for ($i=0;$i<strlen($text);$i++) {
        if (($text{$i}=='"')||($text{$i}=="'")) {
            $returnValue = "no";
        }
    }
    for ($i=0;$i<strlen($images);$i++) {
        if (($images{$i}=='"')||($images{$i}=="'")) {
            $returnValue = "no";
        }
    }
    if (!ctype_digit($price)) {
        $returnValue = "no";
    } else {
        if ((int)$price<0) {
            $returnValue = "no";
        }
    }

    return $returnValue;
}
/*
 Функция добавляет объявление в базу, в случае успеха возвращает yes,
в случае неудачи возвращает no
 */
function addAd($link,$id, $head, $text, $images, $price) {
    $sql  = "INSERT INTO `ads`(`id`, `header`, `text`, `images`, `price`) VALUES ($id, '$head', '$text', '$images', $price)";
//    mysqli_set_charset($link, "utf8");
//    $result = mysqli_query($link, $sql);
    $result = $link->query($sql);
    if ($result == false) {
        return "no";
    } else {
        return "yes";
    }
}

