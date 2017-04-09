<?php
namespace ShavingShop\Utils;

/**
 * Расширения над базовым классом даты/времени с возможностью подмены текущего
 * времени
 */
class DateTime extends \DateTime
{

    private static $currentDate;

    /**
     * Возвращает текущую дату
     * @return self
     */
    public static function now()
    {
        return new self(self::$currentDate);
    }

    /**
     * Устанавливает текущую дату
     * @param string $date описание даты
     */
    public static function setCurrentDate(string $date)
    {
        self::$currentDate = $date;
    }

    /**
     * Восстанавливает поведение {@see now()}
     */
    public static function clearCurrentDate()
    {
        self::$currentDate = 'now';
    }
}
