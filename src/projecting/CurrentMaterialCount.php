<?php
namespace happy\inventory\projecting;

class CurrentMaterialCount extends CurrentCount {

    /** @var string */
    private $category;

    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category) {
        $this->category = $category;
    }

}