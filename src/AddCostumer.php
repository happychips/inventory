<?php
namespace happy\inventory;

use happy\inventory\app\Command;

class AddCostumer extends Command {

    /** @var string */
    private $name;
    /** @var null|string */
    private $location;
    /** @var null|string */
    private $contact;

    /**
     * @param string $name
     * @param null|string $contact
     * @param null|string $location
     */
    public function __construct($name, $contact = null, $location = null) {
        parent::__construct();
        $this->name = $name;
        $this->location = $location;
        $this->contact = $contact;
    }

    /**
     * @return null|string
     */
    public function getLocation() {
        return $this->location;
    }

    /**
     * @return null|string
     */
    public function getContact() {
        return $this->contact;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }
}