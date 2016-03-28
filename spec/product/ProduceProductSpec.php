<?php
namespace spec\happy\inventory\product;
use spec\happy\inventory\scenario\Specification;

/**
 * Products are added to the stock when produced.
 */
class ProduceProductSpec extends Specification {

    function succeed() {
        $this->when->IProduce_UnitsOf(5, 'Chips');
        $this->then->_UnitsOf_ShouldBeProduced(5, 'Chips');
    }
}