<?php
namespace happy\inventory\events;

class MaterialRegistered {
    /** @var string */
    private $name;
    /** @var string */
    private $unit;

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
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUnit() {
        return $this->unit;
    }
}