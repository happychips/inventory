<?php
namespace spec\happy\inventory\material;
use spec\happy\inventory\scenario\Specification;

/**
 * The inventory of a Material can be updated.
 */
class UpdateInventorySpec extends Specification {

    function before() {
        $this->given->IAmLoggedInAs('test');
    }

    function succeed() {
        $this->when->IUpdateTheInventoryOf_To_Units('Potatoes', 15);
        $this->then->TheInventoryOf_ShouldBeUpdatedTo_Units('Potatoes', 15);
    }
}