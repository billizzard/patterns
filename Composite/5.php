<?php

// Избавимся от проблемы реализации ненужных методов в классах листьях, перенеся их в родительский класс.

/**
 * Class UnitException
 * Свой класс Exception
 */
class UnitException extends Exception {};

/**
 * Class Unit
 * Класс Unit, который является родителем всех юнитов (мечников, лучников...)
 */
abstract class Unit {
    /**
     * @return int
     * Каждый юнит обладает какой-то силой атаки
     */
    abstract function attack():int;

    /**
     * @return int
     * Каждый юнит обладает какой-то защитой
     */
    abstract function protection():int;

    /**
     * @param Unit $unit
     * @throws UnitException
     * Метод для добавления юнитов
     */
    public function addUnit(Unit $unit):void
    {
        throw new UnitException(get_class($this) . ' относится к листьям');
    }


    /**
     * @param Unit $unit
     * @throws UnitException
     * Метод для удаления юнитов
     */
    public function removeUnit(Unit $unit):void
    {
        throw new UnitException(get_class($this) . ' относится к листьям');
    }
}

/**
 * Class Army
 * Это класс композит, который может объеденять в себе Unit объекты
 * Юниты могут объеденяться в армии, сила армии будет равнятся сумме сил юнитов
 * Теперь армии и юниты наследуются от общего класса
 */
class Army extends Unit {
    /**
     * @var array
     * Массив который будет хранить всех юнитов, которые есть в армии
     */
    private $units = [];

    /**
     * @param Unit $unit
     * Метод для добавления юнитов в армию
     */
    public function addUnit(Unit $unit):void
    {
        $this->units[] = $unit;
    }

    /**
     * @param Unit $unit
     * Метод для удаления юнитов из набора
     */
    public function removeUnit(Unit $unit):void
    {
        $this->units = array_udiff($this->units, [$unit], function ($a, $b) {
            return ($a === $b) ? 0 : 1;
        });
    }

    /**
     * @return int
     * Вычисляем силу армии перебирая всех юнитов и все армии и складывая их силу
     */
    public function attack():int
    {
        $res = 0;

        /** @var Unit $unit */
        foreach($this->units as $unit) {
            $res += $unit->attack();
        }

        return $res;
    }

    /**
     * @return int
     * Вычисляем защиту армии перебирая всех юнитов и все армии и складывая их защиту
     */
    public function protection():int
    {
        $res = 0;

        /** @var Unit $unit */
        foreach($this->units as $unit) {
            $res += $unit->protection();
        }

        return $res;
    }
}

/**
 * Class Archer
 * Это класс "лист", он не может объеденять в себе объекты
 * и c этим есть проблемы, так как в каждом классе "листе" нам нужно переопределить методы, которые
 * нам не нужны
 */
class Archer extends Unit {
    function attack():int
    {
        return 5;
    }

    function protection():int
    {
        return 5;
    }

}

/**
 * Class Swordsman
 */
class Swordsman extends Unit {
    function attack():int
    {
        return 8;
    }

    function protection():int
    {
        return 8;
    }
}

// ===========================
// ============= Использование
// ===========================

// Создаем нужные клссы и узнаем их стоимость.
$archer1 = new Archer();
$archer2 = new Archer();
$swordsmen1 = new Swordsman();
$swordsmen2 = new Swordsman();

$army1 = new Army();
$army1->addUnit($archer1);
$army1->addUnit($swordsmen1);

$army2 = new Army();
$army2->addUnit($archer2);
$army2->addUnit($swordsmen2);

// Добавлям армию как обычный юнит
$army1->addUnit($army2);

print "Сила армии 1: " . $army1->attack() . "\n";
print "Защита армии 1: " . $army1->protection() . "\n";

// запускаем php 5.php

// Мы избавились от дублирования в классах листьях.
// Но появился недостаток в том, что классы "композиты" (которые в отличии от "листьев" могут объеденять в себе юнитов)
// теперь не обязаны реализовывать методы addUnit и removeUnit что может привести к проблемам, так как мы можем забыть их реализовать.

// Есть способы улучшить шаблон композит, убрав эти недостатки. Но это уже другая история. Суть шаблона в том,
// что мы можем обращаться с одним объектом так же как и с набором объектов.
// клиетскому коду не нужно занть, работает он сейчас с композитом или листом.
