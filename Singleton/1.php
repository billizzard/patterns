<?php
// Суть проста:
// объект должен быть доступен для любого объекта в системе
// в системе не должно быть больше одного такого объекта.

class Singleton {
    protected static $LogFileInstance;

    public static function getInstance() {
        if( !isset(self::$LogFileInstance) ){
            self::$LogFileInstance = new LogClass();
        }

        return self::$LogFileInstance;
    }

    // нельзя создать экземпляр
    private function __construct(){}
    // нельзя клонировать
    private function __clone(){}
}