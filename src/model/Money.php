<?php
namespace happy\inventory\model;

class Money {

    const CURRENCY_BTN = 'BTN';
    const CURRENCY_INR = 'INR';
    const CURRENCY_USD = 'USD';
    const CURRENCY_EUR = 'EUR';

    private static $PRECISION = 10000;

    /** @var int */
    private $amount;
    /** @var self::CURRENCY_* */
    private $currency;

    /**
     * @param float $amount
     * @param self::CURRENCY_* $currency
     */
    public function __construct($amount, $currency) {
        $this->amount = intval($amount * self::$PRECISION);
        $this->currency = $currency;
    }

    /**
     * @return float
     */
    public function getAmount() {
        return $this->amount / self::$PRECISION;
    }

    /**
     * @return self::CURRENCY_*
     */
    public function getCurrency() {
        return $this->currency;
    }

    function __toString() {
        return $this->getAmount() . ' ' . $this->currency;
    }
}