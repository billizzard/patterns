<?php

// Для решения проблемы воспользуемся шаблоном Stategy
// Переместим набор алгоритмов вычисления стоимости в отдельный тип.

/**
 * Class CostStrategy
 * Новый общий класс для классов, в которые мы вынесем логику вычисления стоимости
 */
abstract class CostStrategy {
    abstract function cost(Lesson $lesson):int;
    abstract function chargeType():string;
}

class TimeCostStrategy extends CostStrategy {
    function cost(Lesson $lesson): int
    {
        return (5 * $lesson->getDuration());
    }

    function chargeType(): string
    {
        return "Почасовая оплата";
    }
}

class FixedCostStrategy extends CostStrategy {
    function cost(Lesson $lesson): int
    {
        return 30;
    }

    function chargeType(): string
    {
        return "Фиксированная оплата";
    }
}

/**
 * Class Lesson
 * Общий класс для лекций и семинаров, стоимость которых может считаться от времени либо быть фиксированной
 */
abstract class Lesson {
    protected $duration;
    private $costStrategy;

    function __construct(int $duration, CostStrategy $costStrategy)
    {
        $this->duration = $duration;
        $this->costStrategy = $costStrategy;
    }

    /**
     * @return int
     * Добавили метод для получения продолжительности занятий
     */
    function getDuration():int
    {
        return $this->duration;
    }

    // Просто делегируем вызов метода классу CostStrategy
    function cost():int
    {
        return $this->costStrategy->cost($this);
    }

    // Просто делегируем вызов метода классу CostStrategy
    function chargeType():string
    {
        return $this->costStrategy->chargeType();
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
$lecture1 = new Lecture(5, new FixedCostStrategy());
$lecture2 = new Lecture(5, new TimeCostStrategy());
$seminar = new Seminar(3, new TimeCostStrategy());

print "Lecture1: " . $lecture1->cost() . " - " . $lecture1->chargeType() . "\n";
print "Lecture2: " . $lecture2->cost() . " - " . $lecture2->chargeType() . "\n";
print "Seminar: " . $seminar->cost() . " - " . $seminar->chargeType() . "\n";

// запускаем php 2.php

// Избавились от условного оператора. Структура стала легко расширяема, мы можем добавить еще стратегий не увеличивая до небес класс Lesson. Хорошо читаемый код, есть разделение ответственности между классами
