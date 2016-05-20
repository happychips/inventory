<?php
namespace happy\inventory\projecting;

class CurrentCount {

    /** @var string */
    private $name;
    /** @var string */
    private $unit;
    /** @var float */
    private $amount = 0;

    /**
     * @param string $name
     * @param string $unit
     */
    public function __construct($name, $unit) {
        $this->name = $name;
        $this->unit = $unit;
    }

    /**
     * @return string
     */
    public function getCaption() {
        return $this->name . ' (' . $this->unit . ')';
    }

    public function getAmount() {
        return $this->amount;
    }

    public function addCount($amount) {
        $this->amount += $amount;
    }

    public function subtractCount($amount) {
        $this->amount -= $amount;
    }

    public function setAmount($amount) {
        $this->amount = $amount;
    }
}