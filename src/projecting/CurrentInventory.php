<?php
namespace happy\inventory\projecting;

use happy\inventory\events\DeliveryReceived;
use happy\inventory\events\InventoryUpdated;
use happy\inventory\events\MaterialAcquired;
use happy\inventory\events\MaterialCategorySet;
use happy\inventory\events\MaterialConsumed;
use happy\inventory\events\MaterialRegistered;
use happy\inventory\events\MaterialsAcquired;

class CurrentInventory {

    /** @var CurrentMaterialCount[] */
    private $materials = [];
    /** @var MaterialsAcquired[] */
    private $acquisitions = [];

    /**
     * @return CurrentMaterialCount[]
     */
    public function getMaterials() {
        $materials = array_values($this->materials);

        usort($materials, function (CurrentCount $a, CurrentCount $b) {
            return strcmp($a->getCaption(), $b->getCaption());
        });
        return $materials;
    }

    public function applyMaterialRegistered(MaterialRegistered $e) {
        $this->materials[(string)$e->getMaterial()] = new CurrentMaterialCount($e->getName(), $e->getUnit());
    }

    public function applyMaterialAcquired(MaterialAcquired $e) {
        $this->applyMaterialsAcquired($e->asMultiple());
    }

    public function applyMaterialsAcquired(MaterialsAcquired $e) {
        $this->acquisitions[(string)$e->getAcquisition()] = $e;
    }

    public function applyDeliveryReceived(DeliveryReceived $e) {
        $acquisition = $this->acquisitions[(string)$e->getAcquisition()];

        foreach ($acquisition->getMaterials() as $materialAcquisition) {
            $material = $materialAcquisition->getMaterial();
            $amount = $materialAcquisition->getAmount();

            if ($e->hasDeviantAmount($material)) {
                $amount = $e->getDeviantAmount($material);
            }

            $this->materials[(string)$material]->addCount($amount);
        }
    }

    public function applyMaterialConsumed(MaterialConsumed $e) {
        $this->materials[(string)$e->getMaterial()]->subtractCount($e->getAmount());
    }

    public function applyInventoryUpdated(InventoryUpdated $e) {
        $this->materials[(string)$e->getMaterial()]->setAmount($e->getAmount());
    }

    public function applyMaterialCategorySet(MaterialCategorySet $e) {
        $this->materials[(string)$e->getMaterial()]->setCategory($e->getCategory());
    }
}