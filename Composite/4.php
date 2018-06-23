<?php

// Для решения проблемы используется шаблон Composite.
// Он хорошо применим в том случае, когда группа объектов должна вести себя также, как и один объект
// Т.е. у нас по сути армия ведет себя также как и один объект. Она имеет силу атаки, защиту (передвижение и прочие характеристики)
// Т.е. нам это шаблон подходит хорошо

// Для реализации шаблона нам нужно чтобы у общего класса были:
// 1. Методы для добавления и удаления объектов
// 2. Объекты должны поддерживать общий набор методов (вроде attack)

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
     * Метод для добавления юнитов
     */
    abstract function addUnit(Unit $unit):void;

    /**
     * @param Unit $unit
     * Метод для удаления юнитов
     */
    abstract function removeUnit(Unit $unit):void;
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

    public function addUnit(Unit $unit):void
    {
        throw new UnitException(get_class($this) . ' относится к листьям');
    }

    public function removeUnit(Unit $unit):void
    {
        throw new UnitException(get_class($this) . ' относится к листьям');
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

    public function addUnit(Unit $unit):void
    {
        throw new UnitException(get_class($this) . ' относится к листьям');
    }

    public function removeUnit(Unit $unit):void
    {
        throw new UnitException(get_class($this) . ' относится к листьям');
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

// запускаем php 4.php

// Мы избавились от проблемы перебора армий и любых других объединений, входящих в армию или в другое объединение войск.
// Также теперь интерфейс для добавления армий такой же как и для добавления юнитов (удалили addArmy) и в перспективе не нужно будет добавлять
// новые методы для добавления других груп войск
// но столкнулись с проблемой реализации addUnit и removeUnit в классах листьях
