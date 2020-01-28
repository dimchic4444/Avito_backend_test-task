<?php
require '../functions_for_add_ads.php';
use PHPUnit\Framework\TestCase;

class resultStubForGetUnicalID
{
    public function fetch_assoc()
    {
        return false;
    }
}


class AddAdsTests extends TestCase
{

    public function additionProviderForValidate()
    {
        return [
            ["Отдам котенка в добрые руки", "Мяукает нормально", "cat.png","yes"],
            ["Отдам котенка' в добрые руки", "Мяукает нормально", "","no"],
            ["Отдам котенка в добрые руки", "Мяукает нормально", "cat.png cat1.png cat2.png cat3.png","no"]
        ];
    }
    /**
     * @dataProvider additionProviderForValidate
     */
    public function testValidateFields($a, $b, $c, $expected)
    {
        $result = validateFields($a, $b, $c);
        $this->assertSame($expected, $result);
    }
    public function additionProviderForInjection()
    {
        return [
            ["Отдам котенка в добрые руки", "Мяукает нормально", "cat.png", "100", "yes"],
            ["Отдам котенка' в добры'е руки", "Мяукает нормально", "", "200","no"],
            ["Отдам котенка в добрые руки", 'Мяукает нормал"ьно', "cat.png cat1.png cat2.png cat3.png", "200","no"]
        ];
    }
    /**
     * @dataProvider additionProviderForInjection
     */
    public function testAntiInjection($a, $b, $c, $d, $expected)
    {
        $result = antiSQLInjectionValidate($a, $b, $c, $d);
        $this->assertSame($expected, $result);
    }

/*
 Создаем мок объекта mysqli, который в функции query вернет нам объект типа resultStub
 Метод которого fetch_assoc вернет нам false (это значит что такого ID
 В базе данных не обнаружено, а соответственно сгенерированное значение
 Уникально. Функция getUniqueID должна вернуть любое число
 */
    public function testGetUniqueID() {
        $stub = $this->createMock(mysqli::class);
        $stub->method('query')->willReturn(new resultStubForGetUnicalID());
        $this->assertContainsOnly('integer', [getUniqueID($stub)]);
    }
/*
 Создаем мок объекта mysqli, который в функции query вернет нам true
 Что означает что объявление успешно добавлено в базу данных
 Если функция addAd вернет true, значи объявление успешно добавлено в базу
 */
    public function testAddAd() {
        $stub = $this->createMock(mysqli::class);
        $stub->method('query')->willReturn("true");
        $this->assertSame('yes', addAd($stub,"228","Телефон","Звонит как надо","tel.jpg", "3000"));
    }
}