<?php
namespace spec\happy\inventory\material;
use spec\happy\inventory\scenario\Specification;

/**
 * The inventory of a Material is lowered by the consumed amount.
 */
class ConsumeMaterialSpec extends Specification {

    function before() {
        $this->given->IAmLoggedInAs('test');
    }

    function succeed() {
        $this->when->IConsume_UnitsOf(6, 'Potatoes');
        $this->then->_UnitsOf_ShouldBeConsumed(6, 'Potatoes');
    }
}