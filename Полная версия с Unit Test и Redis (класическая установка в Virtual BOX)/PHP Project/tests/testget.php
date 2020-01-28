<?php
require '../functions_for_getting_ads.php';
use PHPUnit\Framework\TestCase;

class resultStubForGetAdFromDataBase
{
    public function fetch_assoc()
    {
        return array('id' => '3', 'header' => 'h', 'text' => 't', 'images' => 'i', 'price' => 'p');
    }
}

class resultStubForGetIDSFromDataBase
{
    private $i = 0;
    public function fetch_assoc()
    {
        if ($this->i < 10) {
            $this->i += 1;
            return array('id' => '1');
        } else {
            return false;
        }
    }
}

class GetAdsTests extends TestCase
{
    public function additionProviderForValidateID()
    {
        return [
            ["374747", "yes"],
            ["weewhfuiwehf","no"],
            ["12378'd33","no"]
        ];
    }
    /**
     * @dataProvider additionProviderForValidateID
     */
    public function testValidateID($a, $expected)
    {
        $result = validateID($a);
        $this->assertSame($expected, $result);
    }


    public function additionProviderForAddAdKeys()
    {
        return [
            ["id"],
            ["head"],
            ["text"],
            ["images"],
            ["price"]
        ];
    }
    /*
     Создаем мок объекта mysqli, его метод query возвращает объект типа
     resultStubForGetAdFromDataBase, метод fetch_assoc которого
     возвращает ассоциативный массив со всеми полями объявления
     (header - название столбца в базе head - название ключа для поля
     заголовок в структуре программы) проверяем наличие всех полей в
     ответе функции getAdByID
     */

    /**
     * @dataProvider additionProviderForAddAdKeys
     */
    public function testGetAd($a) {
        $stub = $this->createMock(mysqli::class);
        $stub->method('query')->willReturn(new resultStubForGetAdFromDataBase());
        $this->assertArrayHasKey($a, getAdByID('72727', $stub));
    }

    /*
     Создаем мок объекта mysqli, его метод query возвращает объект
     типа resultStubForGetIDSFromDataBase метод fetch_assoc которого
     возвращает ассоциативный массив только с одним ключом - id ровно 10 раз
     Так как функция getIDS возвраащет 10 ID объявлений
     */
    public function testGetIDS() {
        $stub = $this->createMock(mysqli::class);
        $stub->method('query')->willReturn(new resultStubForGetIDSFromDataBase());
        $this->assertCount(10, getIDS('1', 'pricedown', $stub));
    }


}