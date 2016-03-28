<?php
namespace happy\inventory\model;

abstract class Identifier {

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

    public static function generate() {
        return new static(uniqid());
    }
}