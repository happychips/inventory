<?php
namespace happy\inventory\product;

use happy\inventory\scenario\Specification;

/**
 * Products are sold and delivered
 */
class AddCostumerSpec extends Specification {

    function succeed() {
        $this->when->IAddTheCostumer('Shop No 7');
        $this->then->TheCostumer_ShouldBeAdded('Shop No 7');
    }
}