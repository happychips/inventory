<?php
namespace spec\happy\inventory\product;

use spec\happy\inventory\scenario\Specification;

/**
 * Count units of products currently in stock
 */
class UpdateStockSpec extends Specification {

    function before() {
        $this->given->IAmLoggedInAs('nobody');
    }

    function succeed() {
        $this->when->IUpdateTheStockOf_To_Units('Chips', 12);
        $this->then->TheStockOf_ShouldBeUpdatedTo_Units('Chips', 12);
    }
}