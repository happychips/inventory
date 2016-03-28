<?php
namespace happy\inventory\model;

class Identifier {

    /** @var string */
    private $identifier;

    /**
     * @param string $identifier
     */
    public function __construct($identifier) {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getIdentifier() {
        return $this->identifier;
    }

    function __toString() {
        return $this->identifier;
    }
}