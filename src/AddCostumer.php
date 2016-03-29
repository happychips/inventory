<?php
namespace happy\inventory;

use happy\inventory\app\Command;

class AddCostumer extends Command {

    /** @var string */
    private $name;

    /**
     * @param string $name
     */
    public function __construct($name) {
        parent::__construct();
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }
}