<?php

// На помощь приходит шаблон Decorator

/**
 * Class AbstractFoot
 * Родительский класс еды
 */
abstract class AbstractFoot {
    abstract function getHealthy():int;
}

/**
 * Class Food
 */
class Food extends AbstractFoot {
    // По умолчанию полезность еды
    private $healthy = 4;

    function getHealthy():int
    {
        return $this->healthy;
    }
}

/**
 * Class HealthDecorator
 * Создаем АБСТРАКТНЫЙ декоратор, так как мы не будем переопределять метод getHealthy у AbstractFoot
 * и protected метод, который будет содержать еду
 */
abstract class HealthDecorator extends AbstractFoot {
    protected $food;

    function __construct(AbstractFoot $food)
    {
        $this->food = $food;
    }
}

/**
 * Class DirtyDecorator
 * Декоратор, который отвечает за показатели грязной еды
 */
class DirtyDecorator extends HealthDecorator {
    function getHealthy(): int
    {
        return $this->food->getHealthy() - 2;
    }
}

/**
 * Class CleanDecorator
 * Декоратор, который отвечает за показатели чистой еды
 */
class CleanDecorator extends HealthDecorator {
    function getHealthy(): int
    {
        return $this->food->getHealthy() + 2;
    }
}

/**
 * Class OldDecorator
 * Декоратор, который отвечает за показатели старой еды
 */
class OldDecorator extends HealthDecorator {
    function getHealthy(): int
    {
        return $this->food->getHealthy() - 4;
    }
}

// И теперь мы можем получить показатели любой еды, давайте получим показатели старой чистой еды

$food = new OldDecorator(new CleanDecorator(new Food()));
print $food->getHealthy();

// Запускаем php 3.php

