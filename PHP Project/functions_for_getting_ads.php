<?php
use Redis;

/*
  Даная функция проверяет валидность полученного ID от пользователя
 */
function validateID($id) {
    $returnValue = "yes";
    if (!ctype_digit($id)) {
        $returnValue = "no";
    } else {
        if ((int)$id<0) {
            $returnValue = "no";
        }
    }
    return $returnValue;
}

/*
 Функция получает id объявления и объект базы данных, возвращает все данные конкретного объявления
 */
function getAdByID($ID, $link) {
    //Работа с Redis, если не будет работать - закоментить
    $inRedis = false;
    $redis = new Redis();
    try
    {
        $redis->connect('127.0.0.1', 6379);
    }  catch (Exception $e) {
    }
    if ($redis->exists($ID)) {
            echo "in Redis ";
            $inRedis = true;
        } else {
            echo "not In Redis ";
            $inRedis = false;
    }
    //END Работа с Redis
    $sql  = 'SELECT * FROM `ads` WHERE `id` = '.$ID;
    //Если будут проблемы с Redis раскоментировать строчку ниже
//    $result = $link->query($sql);
    //И закоментировать строчку ниже
    if ($inRedis) {$result = unserialize($redis->get($ID));} else {$result = $link->query($sql);}

    if ($result == false) {
//        print("Произошла ошибка при выполнении запроса");
    } else {
        //Если будут проблемы с Redis - раскоментить строчку ниже
//        $row = $result->fetch_assoc();
        //И закоментить строчку ниже
        if (!$inRedis) {$row = $result->fetch_assoc();} else {$row = $result;}
        if ($row["id"]) {
            //Работа с Redis, если не будет работать - закоментить
            $redis->append($row["id"], serialize($row));
            //END Работа с Redis
            $answer = array('id' => $row["id"],
                'head' => $row["header"],
                'text' => $row["text"],
                'images' => $row["images"],
                'price' => $row["price"]);
            return $answer;
        } else {
            return "no";
        }
    }
}

/*
 Данная функция возращает 10 ID объявлений в зависимости
 От того, какую страницу объявлений необходимо вернуть
 А также ID отсортированные по дате или цене в зависимости
 От запроса пользователя
 */
function getIDS($page, $sort, $link) {
    $start = ($page - 1) * 10;
    switch ($sort) {
        case "pricedown":
            $sortBy = "price DESC";
            break;
        case "priceup":
            $sortBy = "price ASC";
            break;
        case "datedown":
            $sortBy = "date DESC";
            break;
        case "dateup":
            $sortBy = "date ASC";
            break;
        default:
            $sortBy = "date DESC";
    }
    $sql = 'SELECT id FROM `ads` ORDER BY '.$sortBy.' LIMIT '.$start.',10';
    $result = $link->query($sql);
    $returnIDS = array();
    while ($row = $result->fetch_assoc()) {
        array_push($returnIDS,$row["id"]);
    }
    return $returnIDS;
}