<?php
namespace happy\inventory\projecting;

use happy\inventory\events\DeliveryReceived;
use happy\inventory\events\InventoryUpdated;
use happy\inventory\events\MaterialAcquired;
use happy\inventory\events\MaterialConsumed;
use happy\inventory\events\MaterialRegistered;

class CurrentInventory {

    /** @var CurrentCount[] */
    private $materials = [];
    /** @var MaterialAcquired[] */
    private $acquisitions = [];

    /**
     * @return CurrentCount[]
     */
    public function getMaterials() {
        $materials = array_values($this->materials);

        usort($materials, function (CurrentCount $a, CurrentCount $b) {
            return strcmp($a->getCaption(), $b->getCaption());
        });
        return $materials;
    }

    public function applyMaterialRegistered(MaterialRegistered $e) {
        $this->materials[(string)$e->getMaterial()] = new CurrentCount($e->getName(), $e->getUnit());
    }

    public function applyMaterialAcquired(MaterialAcquired $e) {
        $this->acquisitions[(string)$e->getAcquisition()] = $e;
    }

    public function applyDeliveryReceived(DeliveryReceived $e) {
        $acquisition = $this->acquisitions[(string)$e->getAcquisition()];
        $material = $acquisition->getMaterial();

        $amount = $e->getAmount();
        if (is_null($amount)) {
            $amount = $acquisition->getAmount();
        }

        $this->materials[(string)$material]->addCount($amount);
    }

    public function applyMaterialConsumed(MaterialConsumed $e) {
        $this->materials[(string)$e->getMaterial()]->subtractCount($e->getAmount());
    }

    public function applyInventoryUpdated(InventoryUpdated $e) {
        $this->materials[(string)$e->getMaterial()]->setAmount($e->getAmount());
    }
}