<?php

// Этот шаблон сам я ни разу не использовал, да и не видел еще ни на одном проекте, но шаблон интересный

/**
 * Class Body
 * Общий класс для корпуса машин
 */
abstract class Body {
    abstract function getProtection();
}

/**
 * Class MetalBody
 * Корпус из метала
 */
class MetalBody extends Body {
    function getProtection(): int
    {
        return 7;
    }
}

/**
 * Class GlassBody
 * Корпус из стекла
 */
class GlassBody extends Body {
    function getProtection(): int
    {
        return 1;
    }
}

/**
 * Class PlasticBody
 * Корпус из пластика
 */
class PlasticBody extends Body {
    function getProtection(): int
    {
        return 3;
    }
}


/**
 * Class Wheel
 * Общий класс для колес
 */
abstract class Wheel {
    abstract function getMaxSpeed();
    abstract function getRadius();
}

/**
 * Class WheelNormal
 * Обычное колесо
 */
class WheelNormal extends Wheel{
    function getMaxSpeed(): int
    {
        return 90;
    }

    function getRadius(): float
    {
        return 16;
    }
}

/**
 * Class WheelBig
 * Большое колесо
 */
class WheelBig extends Wheel{
    function getMaxSpeed(): int
    {
        return 150;
    }

    function getRadius(): float
    {
        return 22;
    }
}

/**
 * Class WheelSmall
 * Маленькое колесо
 */
class WheelSmall extends Wheel{
    function getMaxSpeed(): int
    {
        return 60;
    }

    function getRadius(): float
    {
        return 13;
    }
}

/**
 * Class CarFactory
 * А вот и наш прототип
 */
class CarFactory {
    private $leftFrontWheel;
    private $rightFrontWheel;
    private $leftBackWheel;
    private $rightBackWheel;
    private $body;

    function __construct(Wheel $LFWheel, Wheel $RFWheel, Wheel $LBWheel, Wheel $RBWheel, Body $body)
    {
        $this->leftFrontWheel = $LFWheel;
        $this->rightFrontWheel = $RFWheel;
        $this->leftBackWheel = $LBWheel;
        $this->rightBackWheel = $RBWheel;
        $this->body = $body;
    }

    function getLeftFrontWheel(): Wheel
    {
        return clone $this->leftFrontWheel;
    }

    function getRightFrontWheel(): Wheel
    {
        return clone $this->rightFrontWheel;
    }

    function getLeftBackWheel(): Wheel
    {
        return clone $this->leftBackWheel;
    }

    function getRightBackWheel(): Wheel
    {
        return clone $this->rightBackWheel;
    }

    function getBody(): Body
    {
        return clone $this->body;
    }

}

// ===========================
// ============= Использование
// ===========================

// Создаем фабрику для создания определенных машин, у которых определенный набор колес и корпуса
$strangeCar = new CarFactory(new WheelSmall, new WheelBig, new WheelSmall, new WheelNormal(), new GlassBody());
$normalCar = new CarFactory(new WheelNormal, new WheelNormal, new WheelNormal, new WheelNormal(), new MetalBody());
// Получаем корпус нормальной машины для чего-то
$normalCar->getBody();

// Получаем корпус странной машины для чего-то
$normalCar->getBody();

// У нас всегда будут независимые объекты, можно даже сразу задавать каки-то параметры (допустим колесам стеметнь износа)
// $strangeCar = new CarFactory(new WheelSmall(10), new WheelBig(2), new WheelSmall, new WheelNormal(), new GlassBody());