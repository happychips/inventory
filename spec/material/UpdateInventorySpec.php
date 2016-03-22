<?php
namespace happy\inventory\material;
use happy\inventory\scenario\Specification;

/**
 * The inventory of a Material can be updated.
 */
class UpdateInventorySpec extends Specification {

    function succeed() {
        $this->when->IUpdateTheInventoryOf_To_Units('Potatoes', 15);
        $this->then->TheInventoryOf_ShouldBeUpdatedTo_Units('Potatoes', 15);
    }
}