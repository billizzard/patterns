<?php

// Реализация AbstractFactory

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
 * Создаем класс с реализацией всех функций, без учета языков, а те кто будет наследоваться, будут их реализовывать для
 * какого-то одного языка
 */
abstract class EncoderManager {
    abstract function getFileSubstrEncoder():LangFileEncoder;
    abstract function getFileHelloEncoder():LangFileEncoder;
    abstract function getConsoleHelloEncoder():LangConsoleEncoder;
}

/**
 * Class PhpEncoderManager
 * Содержит реализацию всех функций для PHP
 */
class PhpEncoderManager extends EncoderManager {
    function getFileSubstrEncoder(): LangFileEncoder
    {
        return new PhpSubstrEncoder();
    }

    function getFileHelloEncoder(): LangFileEncoder
    {
        return new PhpHelloEncoder();
    }

    function getConsoleHelloEncoder(): LangConsoleEncoder
    {
        return new PhpConsoleHelloEncoder();
    }
}

/**
 * Class JsEncoderManager
 * Содержит реализацию всех функций для JS
 */
class JsEncoderManager extends EncoderManager {
    function getFileSubstrEncoder(): LangFileEncoder
    {
        return new JsSubstrEncoder();
    }

    function getFileHelloEncoder(): LangFileEncoder
    {
        return new JsHelloEncoder();
    }

    function getConsoleHelloEncoder(): LangConsoleEncoder
    {
        return new JsConsoleHelloEncoder();
    }
}

// ===========================
// ============= Использование
// ===========================

// Создаем менеджер
$encoderManager = new PhpEncoderManager();
// Получаем encoder
$helloEncoder = $encoderManager->getFileHelloEncoder();
$substrEncoder = $encoderManager->getFileSubstrEncoder();
$consoleHelloEncoder = $encoderManager->getConsoleHelloEncoder();
// Декодируем
print $helloEncoder->encode();
print $substrEncoder->encode();
print $consoleHelloEncoder->consoleEncode();

// Запускаем: php 4.php

// Казалось бы, что все работает так как и раньше, так оно и есть. Но:
// код стал более понятен.
// код стал легко расширяем (раньше при добавлении еще одного языка, нужно было бы во всех switch проставить новую константу, теперь их вообще нету)
// мы можем добавлять любое количество других языков (помимо php и js) и функций кодирования, и это делать понятно и легко
// мы "сгруппировали" функции, теперь работая с PhpEncodeManager мы уверены что работаем с php функциями