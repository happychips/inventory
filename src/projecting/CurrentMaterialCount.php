<?php
namespace happy\inventory\projecting;

class CurrentMaterialCount {

    /** @var string */
    private $name;
    /** @var string */
    private $unit;
    /** @var int */
    private $count = 0;

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

    public function getCount() {
        return $this->count;
    }

    public function addCount($amount) {
        $this->count += $amount;
    }

    public function subtractCount($amount) {
        $this->count -= $amount;
    }

    public function setCount($amount) {
        $this->count = $amount;
    }
}