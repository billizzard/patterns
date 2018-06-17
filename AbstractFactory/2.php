<?php

// Немного усложним код, чтобы увидеть проблему
// Добавим классы которые показывают реализацию функции substr для разных языков

/**
 * Class LangFileEncoder
 * Общий класс для encoders
 */
abstract class LangFileEncoder {
    abstract function encode();
}

/**
 * Class PhpHelloEncoder
 * Кодирует фразу hello для PHP
 */
class PhpHelloEncoder extends LangFileEncoder {
    function encode(): string
    {
        return "<?php echo 'Hello' ?>";
    }
}

/**
 * Class JsHelloEncoder
 * Кодирует фразу hello для JS
 */
class JsHelloEncoder extends LangFileEncoder {
    function encode(): string
    {
        return "<script>alert('Hello')</script>";
    }
}

/**
 * Class PhpSubstrEncoder
 * Кодирует обрезание строки для PHP
 */
class PhpSubstrEncoder extends LangFileEncoder {
    function encode(): string
    {
        return "<?php substr('abcdef' , 3) ?>";
    }
}

/**
 * Class JsSubstrEncoder
 * Кодирует обрезание строки для JS
 */
class JsSubstrEncoder extends LangFileEncoder {
    function encode(): string
    {
        return "<script>'abcdef'.substring(3)</script>";
    }
}

/**
 * Class EncoderManager
 * Создает нужный нам Encoder
 */
class EncoderManager {
    const PHP = 1;
    const JS = 2;
    private $mode;

    function __construct(int $mode)
    {
        $this->mode = $mode;
    }

    // Видим что появился еще один switch, который тоже зависит от mode
    function getFileSubstrEncoder(): LangFileEncoder
    {
        switch($this->mode) {
            case self::JS:
                return new JsSubstrEncoder();
            default:
                return new PhpSubstrEncoder();
        }
    }

    function getFileHelloEncoder(): LangFileEncoder
    {
        switch($this->mode) {
            case self::JS:
                return new JsHelloEncoder();
            default:
                return new PhpHelloEncoder();
        }
    }
}

// ===========================
// ============= Использование
// ===========================

// Создаем менеджер, передавая в конструктор тип енкодера
$encoderManager = new EncoderManager(EncoderManager::PHP);
// Получаем encoder
$helloEncoder = $encoderManager->getFileHelloEncoder();
$substrEncoder = $encoderManager->getFileSubstrEncoder();
// Декодируем
print $helloEncoder->encode();
print $substrEncoder->encode();

// Запускаем: php 3.php

// Видим что с этим нужно что-то делать, наш EncoderManager разрастается, если добавим еще один язык и пару функций будет вообще беда
// Разобраться в коде все сложнее