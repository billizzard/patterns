<?php

//  Это второй вариант Observer, более сложный для понимания.

/**
 * Interface Observer
 * Этот интерфейс будут реализовывать классы, которые будут подписываться на события
 */
interface Observer {
    function update(Observable $observable);
}

/**
 * Class CarObserver
 * Класс который будут наследовать те классы, которые будут подписываться на события
 * Особенность в том, что мы сразу подписываем объект на события объекта Car и мы знаем что будет работать с Car
 */
abstract class CarObserver implements Observer{
    private $car;

    function __construct(Car $car)
    {
        $this->car = $car;
        // вот тут мы сразу подписываемся на события объекта Car, который должны передать в конструкторе
        $car->attach($this);
    }

    function update(Observable $observable)
    {
        // Если это именно тот объект car на который мы подписывались, а не другой
        if ($observable === $this->car) {
            $this->doUpdate($observable);
        }
    }

    abstract function doUpdate(Car $car);

}

/**
 * Interface Observable
 * Этот интерфейс будут реализовывать классы, которые будут генерить события, на которые другие будут подписываться
 */
interface Observable {
    function attach(Observer $observer);
    function detach(Observer $observer);
    function notify();
}

/**
 * Class Car
 * Класс машины, который при закрытии или открытии машины будет генерировать соответствующие события, а те кто будет подписан
 * смогут на них реагировать
 */
class Car implements Observable {
    // Действия закрытия машины и открытия
    const ACTION_CLOSE = 1;
    const ACTION_OPEN = 2;
    // Тут будет сохранятся текущее действие
    private $action;
    // Тут будет хранить всех подписчиков на события
    private $observers = [];

    /**
     * @param Observer $observer
     * Метод для добавления подписчиков на события
     */
    function attach(Observer $observer): void
    {
        $this->observers[] = $observer;
    }

    /**
     * @param Observer $observer
     * Метод для удаления подписчиков от событий
     */
    function detach(Observer $observer) : void
    {
        $this->observers = array_filter($this->observers,
            function($observerFromArray) use ($observer) {
                return $observerFromArray !== $observer;
            }
        );
    }

    /**
     * Метод для оповещения всех подписчиков о том, что произошло что-то
     */
    function notify(): void
    {
        // Перебираем всех подписчиков и вызываем у них метод update, он у них должен быть, так как они должны реализовывать
        // интерфейс Observer
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    function getAction():int
    {
        return $this->action;
    }

    /**
     * Метод для закрытия машины, меняет action и оповещает всех о том, что что-то произошло
     */
    function closeCar(): void
    {
        // do something
        $this->action = self::ACTION_CLOSE;
        $this->notify();
    }

    /**
     * Метод для открытия машины, меняет action и оповещает всех о том, что что-то произошло
     */
    function openCar(): void
    {
        // do something
        $this->action = self::ACTION_OPEN;
        $this->notify();
    }
}

/**
 * Class Signaling
 * Класс Сигнализации, который при закрытии машины, должен включать сигнализацию, а при открытии отключать
 * Теперь наследуем определенный Observer - CarObserver
 */
class Signaling extends CarObserver {
    // имя просто для того чтобы нам различать сигнализации
    private $name;

    // Изменился конструктор, теперь мы сюда передаем объект, на который подписываемся, согласно классу, который наследуем
    function __construct(Car $car, string $name)
    {
        parent::__construct($car);
        $this->name = $name;
    }

    /**
     * @param Car $car
     * Реализует интерфейс, при закрытии машины, включает сигнализацию
     * при открытии отключает
     */
    function doUpdate(Car $car): void
    {
        // теперь тут нет косяка, мы знаем что работаем с объектом Car
        $action = $car->getAction();
        // тут можем реализовать свою логику реагирования на события, в засивимости от типа

        if ($action == Car::ACTION_CLOSE) {// Если мы закрыли машину, то включить сигнализацию
            $this->enable();
        } else if ($action == Car::ACTION_OPEN) {// Если мы открыли машину, то отключить сигнализацию
            $this->disable();
        }
    }

    function enable(): void
    {
        print "enable signaling " . $this->name . "\n";
    }

    function disable(): void
    {
        print "disable signaling " . $this->name . "\n";
    }
}

// ===========================
// ============= Использование
// ===========================
// Создаем объекты машины и сигнализации (допустим их две)
$car = new Car();
// Теперь самостоянельно не нужно делать attach, он делается при создании синализации в абстрактном классе
$signalingFirst = new Signaling($car,'First');
$signalingSecond = new Signaling($car, 'Second');

// Мы закрываем машину, и автоматически после этого будут включены наши две сигнализации
$car->closeCar();
print "----------------------\n";
// Отписываем вторую сигнализацию от события машины
$car->detach($signalingSecond);
// Мы открываем машину и отключится только одна сигнализация, так как вторую мы отписали
// и она не будет реагировать на события машины
$car->openCar();
// запускаем php 2.php