<?php
namespace happy\inventory;

use happy\inventory\app\Command;

class RegisterMaterial extends Command {

    /** @var string */
    private $name;
    /** @var string */
    private $unit;

    /**
     * @param string $name
     * @param string $unit
     * @param \DateTimeImmutable|null $when
     * @throws \Exception
     */
    public function __construct($name, $unit, \DateTimeImmutable $when = null) {
        parent::__construct($when);

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