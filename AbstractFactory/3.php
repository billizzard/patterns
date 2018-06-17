<?php

// Придется еще немного усложнить ситуацию
// Добавлю классы для реализации в консоли тех же функций для разных языков

// ============================== ДЛЯ КОНСОЛИ

/**
 * Class LangConsoleEncoder
 * Родитель для классов encoders для консоли
 */
abstract class LangConsoleEncoder {
    // Имя изменено для того чтобы показать что это другой тип классов
    abstract function consoleEncode();
}

/**
 * Class PhpConsoleHelloEncoder
 * Кодирует фразу для консоли в PHP
 */
class PhpConsoleHelloEncoder extends LangConsoleEncoder {
    function consoleEncode(): string
    {
        return "php -r 'echo \"Hello\";'";
    }
}

/**
 * Class JsConsoleHelloEncoder
 * Кодирует фразу для консоли в JS
 */
class JsConsoleHelloEncoder extends LangConsoleEncoder {
    function consoleEncode(): string
    {
        return "<script>console.log('Hello')</script>";
    }
}

// ============================== КОНЕЦ ДЛЯ КОНСОЛИ


// ============================== ДЛЯ ФАЙЛОВ
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

// ============================== КОНЕЦ ДЛЯ ФАЙЛОВ

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

    function getConsoleHelloEncoder(): LangConsoleEncoder
    {
        switch($this->mode) {
            case self::JS:
                return new JsConsoleHelloEncoder();
            default:
                return new PhpConsoleHelloEncoder();
        }
    }

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
$consoleHelloEncoder = $encoderManager->getConsoleHelloEncoder();
// Декодируем
print $helloEncoder->encode();
print $substrEncoder->encode();
print $consoleHelloEncoder->consoleEncode();

// Запускаем: php 3.php

// Видим что с этим нужно что-то делать, наш EncoderManager разрастается, если добавим еще один язык будет вообще беда
// Разобраться в коде все сложнее