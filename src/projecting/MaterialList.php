<?php
namespace happy\inventory\projecting;

use happy\inventory\events\MaterialRegistered;

class MaterialList {

    /** @var array */
    private $materials = [];

    public function applyMaterialRegistered(MaterialRegistered $e) {
        $this->materials[(string)$e->getMaterial()] = $e->getName() . ' (' . $e->getUnit() . ')';
    }

    /**
     * @return array
     */
    public function getMaterials() {
        asort($this->materials);
        return $this->materials;
    }
}