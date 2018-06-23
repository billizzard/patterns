<?php

// Добавим к предыдущему примеру показатели защиты для юнитов

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
}

/**
 * Class Archer
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

/**
 * Class Army
 * Юниты могут объеденяться в армии, сила армии будет равнятся сумме сил юнитов
 */
class Army {
    /**
     * @var array
     * Массив который будет хранить всех юнитов, которые есть в армии
     */
    private $units = [];
    /**
     * @var array
     * Для объеденения армий в армию, нужен массив, который будет хранить присоединившиеся армии
     */
    private $armies = [];

    /**
     * @param Unit $unit
     * Метод для добавления юнитов в армию
     */
    public function addUnit(Unit $unit):void
    {
        $this->units[] = $unit;
    }

    /**
     * @param Army $army
     * Метод для добавления армий к армии
     */
    public function addArmy(Army $army):void
    {
        $this->armies[] = $army;
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

        /** @var Army $army */
        foreach($this->armies as $army) {
            $res += $army->attack();
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

        /** @var Army $army */
        foreach($this->armies as $army) {
            $res += $army->protection();
        }

        return $res;
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

$army1->addArmy($army2);

print "Сила армии 1: " . $army1->attack() . "\n";
print "Защита армии 1: " . $army1->protection() . "\n";

// запускаем php 3.php

// Проблемы становятся более заметными, особенно если мы думем о добавлении других видов объединений или новых параметров юнитов
