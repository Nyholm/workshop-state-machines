<?php

declare(strict_types=1);

namespace App;

/**
 * A nice little hack to help us move time back and forth.
 */
class WorldClock
{
    private static $currentDate = '2018-08-05 14:00:00';

    public static function getCurrentTimestamp(): int
    {
        return strtotime(self::$currentDate);
    }

    /**
     * Get a date relative the fake time.
     *
     * @param string $time
     * @return \DateTimeImmutable
     */
    public static function getDateTimeRelativeFakeTime($time = "now")
    {
        $actualTime = new \DateTimeImmutable();
        $requestedTime =  new \DateTimeImmutable($time);
        $diff = $actualTime->diff($requestedTime);

        $fakedDateTime = (new \DateTimeImmutable())->setTimestamp(self::getCurrentTimestamp());

        return $fakedDateTime->add($diff);
    }
}
