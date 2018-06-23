<?php

// Есть юниты с определенной силой атаки, которые могут объеденяться в армии

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
}

/**
 * Class Archer
 */
class Archer extends Unit {
    function attack():int
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
     * @param Unit $unit
     * Метод для добавления юнитов в армию
     */
    public function addUnit(Unit $unit):void
    {
        $this->units[] = $unit;
    }

    /**
     * @return int
     * Вычисляем силу армии перебирая всех юнитов и складывая их силу
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
}
// ===========================
// ============= Использование
// ===========================

// Создаем нужные клссы и узнаем их стоимость.
$archer1 = new Archer();
$archer2 = new Archer();
$swordsmen1 = new Swordsman();
$army1 = new Army();
$army1->addUnit($archer1);
$army1->addUnit($archer2);
$army1->addUnit($swordsmen1);

print "Сила армии 1: " . $army1->attack() . "\n";

// запускаем php 1.php

// Пока все выглядит неплохо
