<?php


//  Это третий вариант, только для PHP, так как содержит уже сделанные для такой задачи внутренние классы
// php для реализации обсерверов SplObserver - тот кто будет реагировать, SplSubject - то кто будет генерировать события
// и SplStorage - который будет хранить обсерверы и сам делать detach

// По сути происходить будет все то же самое что и в 2.php, просто меньше кода, так как используем внутренние классы

/**
 * Class CarObserver
 * Класс который будут наследовать те классы, которые будут подписываться на события
 * Особенность в том, что мы сразу подписываем объект на события объекта Car и мы знаем что будет работать с Car
 * Теперь реализует встроенный интерфейс, котоырй нам самим раньше приходилось писать
 */
abstract class CarObserver implements SplObserver{
    private $car;

    function __construct(Car $car)
    {
        $this->car = $car;
        // вот тут мы сразу подписываемся на события объекта Car, который должны передать в конструкторе
        $car->attach($this);
    }

    function update(SplSubject $observable)
    {
        // Если это именно тот объект car на который мы подписывались, а не другой
        if ($observable === $this->car) {
            $this->doUpdate($observable);
        }
    }

    abstract function doUpdate(Car $car);
}

/**
 * Class Car
 * Класс машины, который при закрытии или открытии машины будет генерировать соответствующие события, а те кто будет подписан
 * смогут на них реагировать
 * Теперь реализует встроенный интерфейс, который нам приходилось самим писать
 */
class Car implements SplSubject {
    // Действия закрытия машины и открытия
    const ACTION_CLOSE = 1;
    const ACTION_OPEN = 2;
    // Новая переменная, которая будет хранить observer
    private $storage;
    // Тут будет сохранятся текущее действие
    private $action;

    // Добавляем storage. который сам будет заниматься detach объектов, когда нам нужно
    function __construct()
    {
        $this->storage = new SplObjectStorage();
    }

    /**
     * @param SplObserver $observer
     * Метод для добавления подписчиков на события
     */
    function attach(SplObserver $observer): void
    {
        $this->storage->attach($observer);
    }

    /**
     * @param SplObserver $observer
     * Метод для удаления подписчиков от событий
     * Теперь всю работу делает внутренний класс
     */
    function detach(SplObserver $observer) : void
    {
        $this->storage->detach($observer);
    }

    /**
     * Метод для оповещения всех подписчиков о том, что произошло что-то
     */
    function notify(): void
    {
        // Перебираем всех подписчиков и вызываем у них метод update, он у них должен быть, так как они должны реализовывать
        // интерфейс Observer
        foreach ($this->storage as $observer) {
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
// запускаем php 3.php