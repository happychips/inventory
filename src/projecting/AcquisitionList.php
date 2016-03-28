<?php
namespace happy\inventory\projecting;

use happy\inventory\events\DeliveryReceived;
use happy\inventory\events\MaterialAcquired;

class AcquisitionList {

    private $acquisitions = [];

    /**
     * @param MaterialAcquired $e
     */
    public function applyMaterialAcquired(MaterialAcquired $e) {
        $this->acquisitions[(string)$e->getAcquisition()] = $e->getWhen()->format('Y-m-d') . ' - ' . $e->getMaterial() . ' (' . $e->getAmount() . ')';
    }

    public function applyDeliveryReceived(DeliveryReceived $e) {
        unset($this->acquisitions[(string)$e->getAcquisition()]);
    }

    public function getAcquisitions() {
        asort($this->acquisitions);
        return $this->acquisitions;
    }
}