<?php
namespace happy\inventory;

class RegisterMaterial {

    /** @var string */
    private $name;
    /** @var string */
    private $unit;

    /**
     * @param string $name
     * @param string $unit
     * @throws \Exception
     */
    public function __construct($name, $unit) {
        $this->name = trim($name);
        $this->unit = trim($unit);

        if (!$this->name) {
            throw new \Exception('The article name cannot be empty');
        }

        if (!$this->unit) {
            throw new \Exception('The unit cannot be empty');
        }
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getUnit() {
        return $this->unit;
    }
}