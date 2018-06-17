<?php

// Проблемный код
// Есть классы которые показывают различные функции в разных языках

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
// Декодируем
print $helloEncoder->encode();

// Запускаем: php 1.php

// Вроде все норм, все работает.