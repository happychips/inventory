<?php
namespace happy\inventory\events;

use happy\inventory\model\SupplierIdentifier;
use happy\inventory\model\UserIdentifier;

class SupplierAdded extends Event {

    /** @var SupplierIdentifier */
    private $supplier;
    /** @var string */
    private $name;

    /**
     * @param SupplierIdentifier $supplier
     * @param string $name
     * @param UserIdentifier $who
     * @param \DateTimeImmutable|null $when
     */
    public function __construct(SupplierIdentifier $supplier, $name, UserIdentifier $who, \DateTimeImmutable $when = null) {
        parent::__construct($who, $when);
        $this->supplier = $supplier;
        $this->name = $name;
    }

    /**
     * @return SupplierIdentifier
     */
    public function getSupplier() {
        return $this->supplier;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }
}