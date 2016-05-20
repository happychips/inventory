<?php
namespace happy\inventory\events;

use happy\inventory\model\MaterialIdentifier;
use happy\inventory\model\UserIdentifier;

class MaterialRegistered extends Event {

    /** @var string */
    private $name;
    /** @var string */
    private $unit;
    /** @var MaterialIdentifier */
    private $material;

    /**
     * @param MaterialIdentifier $material
     * @param string $name
     * @param string $unit
     * @param UserIdentifier $who
     * @param \DateTimeImmutable|null $when
     */
    public function __construct(MaterialIdentifier $material, $name, $unit, UserIdentifier $who, \DateTimeImmutable $when = null) {
        parent::__construct($who, $when);
        $this->name = $name;
        $this->unit = $unit;
        $this->material = $material;
    }

    /**
     * @return MaterialIdentifier
     */
    public function getMaterial() {
        return $this->material;
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