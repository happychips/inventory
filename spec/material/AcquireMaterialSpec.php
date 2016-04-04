<?php
namespace spec\happy\inventory\material;
use spec\happy\inventory\scenario\Specification;

/**
 * An amount of units of a Material can be acquired at a given cost. It is not available until delivered.
 */
class AcquireMaterialSpec extends Specification {

    function before() {
        $this->given->IAmLoggedInAs('admin');
    }

    function succeed() {
        $this->when->IAcquire_UnitsOf_For(12, 'Potatoes', 6, 'BTN');
        $this->then->_UnitsOf_For__ShouldBeAcquired(12, 'Potatoes', 6, 'BTN');
    }

    function withDocuments() {
        $this->when->IAcquire_UnitsOf_WithTheDocuments(12, 'Potatoes', ['invoice.pdf', 'order.pdf']);
        $this->then->TheAcquisitionShouldContainTheDocuments(['invoice.pdf', 'order.pdf']);
    }

    function deliveryReceived() {
        $this->given->theNextGeneratedIdentifierIs('Tomatoes_4');
        $this->when->IAcquire_UnitsOf_For_Directly(4, 'Tomatoes', 12, 'BTN');
        $this->then->_ShouldBeReceived('Tomatoes_4');
    }

    function withSupplier() {
        $this->when->IAcquire_UnitsOf_From(4, 'Potatoes', 'Karma');
        $this->then->_UnitsOf_ShouldBeAcquiredFrom(4, 'Potatoes', 'Karma');
    }
}