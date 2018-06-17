<?php

// Усложним модель, чтобы увидеть проблему
// Добавляем просточенную еду (старую) и видим, что нам сложно получить полезность одновременно
// старной и грязной еды

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
 * Class DirtyFoot
 */
class DirtyFoot extends Food {

    function getHealthy():int
    {
        return parent::getHealthy() - 2;
    }
}

/**
 * Class CleanFood
 */
class CleanFood extends Food {

    function getHealthy():int
    {
        return parent::getHealthy() + 2;
    }
}

/**
 * Class OldFood
 * Добавим старую еду
 */
class OldFood extends Food {

    function getHealthy():int
    {
        return parent::getHealthy() - 4;
    }
}



// И теперь видим проблему, как нам получить старую и грязную еду допустим? Создавать класс OldDirtyFood? Понятно что это не выход.

