<?php
namespace happy\inventory\model;

class ExtraCost {

    /** @var Money */
    private $money;
    /** @var string */
    private $reason;

    /**
     * @param Money $money
     * @param string $reason
     */
    public function __construct(Money $money, $reason) {
        $this->money = $money;
        $this->reason = $reason;
    }

    /**
     * @return Money
     */
    public function getMoney() {
        return $this->money;
    }

    /**
     * @return string
     */
    public function getReason() {
        return $this->reason;
    }
}