<?php
namespace happy\inventory;

use happy\inventory\app\Command;

class AddSupplier extends Command {

    /** @var string */
    private $name;

    /**
     * @param string $name
     * @param \DateTimeImmutable $when
     */
    public function __construct($name, \DateTimeImmutable $when = null) {
        parent::__construct($when);
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }
}