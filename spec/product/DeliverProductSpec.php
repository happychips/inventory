<?php
namespace spec\happy\inventory\product;
use spec\happy\inventory\scenario\Specification;

/**
 * Products leaves the stock when they're sold.
 */
class DeliverProductSpec extends Specification {

    function before() {
        $this->given->IAmLoggedInAs('nobody');
    }

    function succeed() {
        $this->when->IDeliver_UnitsOf_To(6, 'Chips', 'Shop No 7');
        $this->then->_UnitsOf_ShouldBeDeliveredTo(6, 'Chips', 'Shop No 7');
    }
}