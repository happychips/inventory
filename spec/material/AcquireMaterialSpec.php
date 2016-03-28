<?php
namespace spec\happy\inventory\material;
use spec\happy\inventory\scenario\Specification;

/**
 * An amount of units of a Material can be acquired at a given cost. It is not available until delivered.
 */
class AcquireMaterialSpec extends Specification {

    function succeed() {
        $this->when->IAcquire_UnitsOf_For(12, 'Potatoes', 6, 'BTN');
        $this->then->_UnitsOf_For__ShouldBeAcquired(12, 'Potatoes', 6, 'BTN');
    }

    function withDocuments() {
        $this->when->IAcquire_UnitsOf_WithTheDocuments(12, 'Potatoes', ['invoice.pdf', 'order.pdf']);
        $this->then->TheAcquisitionShouldContainTheDocuments(['invoice.pdf', 'order.pdf']);
    }
}