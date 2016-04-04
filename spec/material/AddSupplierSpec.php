<?php
namespace spec\happy\inventory\material;
use spec\happy\inventory\scenario\Specification;

/**
 * Suppliers are the source of Materials
 */
class AddSupplierSpec extends Specification {

    function before() {
        $this->given->IAmLoggedInAs('nobody');
    }

    function succeed() {
        $this->when->IAddTheSupplier('Karma');
        $this->then->TheSupplier_ShouldBeAdded('Karma');
    }

    function supplierAlreadyAdded() {
        $this->given->IAddedASupplier('Karma');
        $this->tryThat->IAddTheSupplier('Karma');
        $this->then->ItShouldFailWith('A supplier with that name was already added.');
    }
}