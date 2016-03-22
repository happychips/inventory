<?php
namespace happy\inventory\product;

use happy\inventory\scenario\Specification;

/**
 * Count units of products currently in stock
 */
class UpdateStockSpec extends Specification {

    function succeed() {
        $this->when->IUpdateTheStockOf_To_Units('Chips', 12);
        $this->then->TheStockOf_ShouldBeUpdatedTo_Units('Child', 12);
    }
}