<?php

//  Это первый вариант Observer, легкий для понимания, но не без проблем

/**
 * Interface Observer
 * Этот интерфейс будут реализовывать классы, которые будут подписываться на события
 */
interface Observer {
    function update(Observable $observable);
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
 */
class Signaling implements Observer {
    // имя просто для того чтобы нам различать сигнализации
    private $name;

    function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param Observable $observable
     * Реализует интерфейс, при закрытии машины, включает сигнализацию
     * при открытии отключает
     */
    function update(Observable $observable): void
    {
        // вот тут косяк, у интерфейса Observable нету метода getAction он есть у класса Car
        // т.е. нет никакой гарантии что это действительно объект класса Car, у которого есть этот метод
        // но для показа принципа, это мы допускаем в этой версии реализации
        $action = $observable->getAction();
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
$signalingFirst = new Signaling('First');
$signalingSecond = new Signaling('Second');
// Подписываем первую сигнализацию на события машины
$car->attach($signalingFirst);
// Подписываем вторую сигнализацию на события машины
$car->attach($signalingSecond);
// Мы закрываем машину, и автоматически после этого будут включены наши две сигнализации
$car->closeCar();
print "----------------------\n";
// Отписываем вторую сигнализацию от события машины
$car->detach($signalingSecond);
// Мы открываем машину и отключится только одна сигнализация, так как вторую мы отписали
// и она не будет реагировать на события машины
$car->openCar();
// запускаем php 1.php