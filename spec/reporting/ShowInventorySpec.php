<?php
namespace spec\happy\inventory\reporting;

use spec\happy\inventory\scenario\Specification;

class ShowInventorySpec extends Specification {

    function before() {
        $this->given->IAmLoggedInAs('test');
    }

    function noMaterials() {
        $this->when->IShowTheInventory();
        $this->then->TheInventoryShouldContain_Materials(0);
    }

    function noInventory() {
        $this->given->IRegisteredTheMaterial_WithTheUnit('Potatoes', 'kg');
        $this->when->IShowTheInventory();
        $this->then->TheInventoryShouldContain_Materials(1);
        $this->then->MaterialOfTheInventory_ShouldHaveTheCaption(1, 'Potatoes (kg)');
        $this->then->MaterialOfTheInventory_ShouldHaveTheCount(1, 0);
    }

    function sortByName() {
        $this->given->IRegisteredTheMaterial_WithTheUnit('Tomatoes', 'piece');
        $this->given->IRegisteredTheMaterial_WithTheUnit('Potatoes', 'kg');
        $this->given->IRegisteredTheMaterial_WithTheUnit('Carrots', 'kg');

        $this->when->IShowTheInventory();

        $this->then->TheInventoryShouldContain_Materials(3);
        $this->then->MaterialOfTheInventory_ShouldHaveTheCaption(1, 'Carrots (kg)');
        $this->then->MaterialOfTheInventory_ShouldHaveTheCaption(2, 'Potatoes (kg)');
        $this->then->MaterialOfTheInventory_ShouldHaveTheCaption(3, 'Tomatoes (piece)');
    }

    function materialAcquired() {
        $this->given->IRegisteredTheMaterial_WithTheUnit('Potatoes', 'kg');
        $this->given->IAcquired_Of(5, 'Potatoes');

        $this->when->IShowTheInventory();
        $this->then->MaterialOfTheInventory_ShouldHaveTheCount(1, 0);
    }

    function materialDelivered() {
        $this->given->IRegisteredTheMaterial_WithTheUnit('Potatoes', 'kg');
        $this->given->IAcquired_Of(5, 'Potatoes');
        $this->given->IReceivedTheDeliveryOf(5, 'Potatoes');

        $this->when->IShowTheInventory();
        $this->then->MaterialOfTheInventory_ShouldHaveTheCount(1, 5);
    }

    function differentAmountOfMaterialDelivered() {
        $this->given->IRegisteredTheMaterial_WithTheUnit('Potatoes', 'kg');
        $this->given->IAcquired_Of(5, 'Potatoes');
        $this->given->IReceived_OfTheDeliveryOf(3, 5, 'Potatoes');

        $this->when->IShowTheInventory();
        $this->then->MaterialOfTheInventory_ShouldHaveTheCount(1, 3);
    }

    function severalDeliveries() {
        $this->given->IRegisteredTheMaterial_WithTheUnit('Potatoes', 'kg');
        $this->given->IAcquired_Of(5, 'Potatoes');
        $this->given->IReceivedTheDeliveryOf(5, 'Potatoes');
        $this->given->IReceived_OfTheDeliveryOf(3, 5, 'Potatoes');

        $this->when->IShowTheInventory();
        $this->then->MaterialOfTheInventory_ShouldHaveTheCount(1, 8);
    }

    function materialConsumed() {
        $this->given->IRegisteredTheMaterial_WithTheUnit('Potatoes', 'kg');
        $this->given->IAcquired_Of(5, 'Potatoes');
        $this->given->IReceivedTheDeliveryOf(5, 'Potatoes');
        $this->given->IConsumed__Of(2, 'kg', 'Potatoes');

        $this->when->IShowTheInventory();
        $this->then->MaterialOfTheInventory_ShouldHaveTheCount(1, 3);
    }

    function inventoryUpdated() {
        $this->given->IRegisteredTheMaterial_WithTheUnit('Potatoes', 'kg');
        $this->given->IAcquired_Of(5, 'Potatoes');
        $this->given->IReceivedTheDeliveryOf(5, 'Potatoes');
        $this->given->IConsumed__Of(2, 'kg', 'Potatoes');
        $this->given->IUpdatedTheInventoryOf_To('Potatoes', 4, 'kg');

        $this->when->IShowTheInventory();
        $this->then->MaterialOfTheInventory_ShouldHaveTheCount(1, 4);
    }
}