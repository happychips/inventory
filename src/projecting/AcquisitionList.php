<?php
namespace happy\inventory\projecting;

use happy\inventory\events\DeliveryReceived;
use happy\inventory\events\MaterialAcquired;
use happy\inventory\events\MaterialsAcquired;
use happy\inventory\model\MaterialAcquisition;

class AcquisitionList {

    private $acquisitions = [];

    /**
     * @param MaterialAcquired $e
     */
    public function applyMaterialAcquired(MaterialAcquired $e) {
        $this->applyMaterialsAcquired($e->asMultiple());
    }

    /**
     * @param MaterialsAcquired $e
     */
    public function applyMaterialsAcquired(MaterialsAcquired $e) {
        $this->acquisitions[(string)$e->getAcquisition()] = $e->getWhen()->format('Y-m-d') . ' - ' .
            implode(', ', array_map(function (MaterialAcquisition $materialAcquisition) {
                return $materialAcquisition->getMaterial() . '(' . $materialAcquisition->getAmount() . ')';
            }, $e->getMaterials()));
    }

    public function applyDeliveryReceived(DeliveryReceived $e) {
        if (!$e->isPartialDelivery()) {
            unset($this->acquisitions[(string)$e->getAcquisition()]);
        }
    }

    public function getAcquisitions() {
        asort($this->acquisitions);
        return $this->acquisitions;
    }
}