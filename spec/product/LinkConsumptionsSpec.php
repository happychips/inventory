<?php
namespace spec\happy\inventory\product;

use spec\happy\inventory\scenario\Specification;

/**
 * Products can have Materials linked with them of which a certain quantity is consumed on production.
 */
class LinkConsumptionsSpec extends Specification {

    function before() {
        $this->given->IAmLoggedInAs('admin');
        $this->given->IRegisteredTheProduct_WithTheUnit('Chips', 'pack');
        $this->given->IRegisteredTheMaterial_WithTheUnit('Potatoes', 'kg');
        $this->given->IRegisteredTheMaterial_WithTheUnit('Tomatoes', 'kg');
    }

    function setForNotExistingProduct() {
        $this->tryThat->ISetTheConsumptions_For([], 'not existing');
        $this->then->ItShouldFailWith('This product does not exist.');
    }

    function setForNotExistingMaterial() {
        $this->tryThat->ISetTheConsumptions_For([[0, 'not existing']], 'Chips_pack');
        $this->then->ItShouldFailWith('Material [not existing] does not exist.');
    }

    function setNoConsumptions() {
        $this->when->ISetTheConsumptions_For([], 'Chips_pack');
        $this->then->TheConsumptions_ShouldBeSetFor([], 'Chips_pack');
    }

    function setConsumptions() {
        $this->when->ISetTheConsumptions_For([[3, 'Potatoes_kg'], [2, 'Tomatoes_kg']], 'Chips_pack');
        $this->then->TheConsumptions_ShouldBeSetFor([[3, 'Potatoes_kg'], [2, 'Tomatoes_kg']], 'Chips_pack');
    }

    function consumeOnProduction() {
        $this->given->ISetTheConsumptions_For([[3, 'Potatoes_kg'], [2, 'Tomatoes_kg']], 'Chips_pack');
        $this->when->IProduce_UnitsOf(2.5, 'Chips_pack');
        $this->then->_UnitsOf_ShouldBeConsumed(7.5, 'Potatoes');
        $this->then->_UnitsOf_ShouldBeConsumed(5, 'Tomatoes');
    }

    function listWithNoConsumptions() {
        $this->when->IListLinkedConsumptions();
        $this->then->ItShouldListTheLinkedConsumptionsOf_Products(1);
        $this->then->TheLinkedConsumptionsOfProduct_ShouldBe(1, []);
    }

    function listWithConsumptions() {
        $this->given->ISetTheConsumptions_For([[1, 'Potatoes_kg']], 'Chips_pack');
        $this->given->ISetTheConsumptions_For([[3, 'Potatoes_kg'], [2, 'Tomatoes_kg']], 'Chips_pack');
        $this->when->IListLinkedConsumptions();
        $this->then->ItShouldListTheLinkedConsumptionsOf_Products(1);
        $this->then->TheLinkedConsumptionsOfProduct_ShouldBe(1, [[3, 'Potatoes_kg'], [2, 'Tomatoes_kg']]);
    }

    function listConsumptionsOfProduct() {
        $this->given->ISetTheConsumptions_For([[1, 'Potatoes_kg']], 'Chips_pack');
        $this->given->ISetTheConsumptions_For([[3, 'Potatoes_kg'], [2, 'Tomatoes_kg']], 'Chips_pack');
        $this->when->IListLinkedConsumptionsFor('Chips_pack');
        $this->then->TheLinkedConsumptionsShouldBe([[3, 'Potatoes_kg'], [2, 'Tomatoes_kg']]);
    }
}