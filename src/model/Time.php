<?php
namespace happy\inventory\model;

class Time {

    private static $frozen;

    public static function freeze(\DateTimeImmutable $when) {
        self::$frozen = $when;
    }

    public static function now() {
        return self::$frozen ?: new \DateTimeImmutable();
    }
}