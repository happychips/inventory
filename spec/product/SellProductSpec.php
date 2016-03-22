<?php
namespace happy\inventory\product;
use happy\inventory\scenario\Specification;

/**
 * Products leaves the stock when they're sold.
 */
class SellProductSpec extends Specification {

    function succeed() {
        $this->when->IDeliver_UnitsOf_To(6, 'Chips', 'Shop No 7');
        $this->then->_UnitsOf_ShouldBeDeliveredTo(6, 'Chips', 'Shop No 7');
    }
}