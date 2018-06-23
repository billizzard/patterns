<?php

// Есть Лекции и Семинары, оплата которых может зависеть как от продолжительности, так и быть фиксированной

/**
 * Class Lesson
 * Общий класс для лекций и семинаров, стоимость которых может считаться от времени либо быть фиксированной
 */
abstract class Lesson {

    const FIXED = 1;
    const TIMED = 2;

    protected $duration;
    private $costType;

    function __construct(int $duration, int $costType)
    {
        $this->duration = $duration;
        $this->costType = $costType;
    }

    function cost():int
    {
        switch($this->costType) {
            case self::FIXED:
                return 30;
            case self::TIMED:
                return (5 * $this->duration);
            default:
                $this->costType = self::FIXED;
                return 30;
        }
    }

    function chargeType():string
    {
        switch($this->costType) {
            case self::FIXED:
                return "Фиксированная оплата";
            case self::TIMED:
                return "Почасовая оплата";
            default:
                $this->costType = self::FIXED;
                return "Фиксированная оплата";
        }
    }

}

/**
 * Class Lecture
 */
class Lecture extends Lesson {
    // Что-то специфическое для Lecture
}

/**
 * Class Seminar
 */
class Seminar extends Lesson {
    // Что-то специфическое для Seminar
}

// ===========================
// ============= Использование
// ===========================

// Создаем нужные клссы и узнаем их стоимость.
$lecture1 = new Lecture(5, Lesson::FIXED);
$lecture2 = new Lecture(5, Lesson::TIMED);
$seminar = new Seminar(3, Lesson::TIMED);

print "Lecture1: " . $lecture1->cost() . " - " . $lecture1->chargeType() . "\n";
print "Lecture2: " . $lecture2->cost() . " - " . $lecture2->chargeType() . "\n";
print "Seminar: " . $seminar->cost() . " - " . $seminar->chargeType() . "\n";

// запускаем php 1.php

// Что тут плохого: дублирование условного оператора. Плохая расширяемость структуры. Плохая читаемость при расширени функционала.
