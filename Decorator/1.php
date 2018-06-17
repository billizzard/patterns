<?php

// Это проблемный код, который кажется нормальным
// Есть еда и ее полезность, и есть наследованые классы: грязная еда - с меньшей полезностью, и чистая с большей

/**
 * Class AbstractFoot
 * Родительский класс еды
 */
abstract class AbstractFoot {
    abstract function getHealthy():int;
}

/**
 * Class Food
 * Еда по умолчанию
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
 * Класс для грязной еды
 */
class DirtyFoot extends Food {

    function getHealthy():int
    {
        return parent::getHealthy() - 2;
    }
}


/**
 * Class CleanFood
 * Класс для чистой еды
 */
class CleanFood extends Food {

    function getHealthy():int
    {
        return parent::getHealthy() + 2;
    }
}

// Кажется что все нормально, но гибкость такого подхода не большая
// Получаем полезность чистой еды
$cleanFoot = new CleanFood();
print $cleanFoot->getHealthy();
// Запускаем php 1.php

