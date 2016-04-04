<?php
namespace happy\inventory\model;

class Identifier {

    public static $next;

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

    /**
     * @return static
     */
    public static function generate() {
        $id  = self::$next ?: date('Ymd_His_') . substr(uniqid(), -4);
        self::$next = null;
        return new static($id);
    }
}