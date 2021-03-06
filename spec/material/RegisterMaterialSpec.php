<?php
namespace spec\happy\inventory\material;
use spec\happy\inventory\scenario\Specification;

/**
 * A Material defines the name and unit of something that can be added to the inventory.
 */
class RegisterMaterialSpec extends Specification {

    function before() {
        $this->given->IAmLoggedInAs('admin');
    }

    function nameCannotBeEmpty() {
        $this->tryThat->IRegisterAMaterial_WithTheUnit("  \t ", 'kg');
        $this->then->ItShouldFailWith('The article name cannot be empty');
    }

    function unitCannotBeEmpty() {
        $this->tryThat->IRegisterAMaterial_WithTheUnit('Potatoes', " \t ");
        $this->then->ItShouldFailWith('The unit cannot be empty');
    }

    function succeed() {
        $this->when->IRegisterAMaterial_WithTheUnit('Potatoes', 'kg');
        $this->then->AMaterial_WithTheUnit_ShouldBeRegistered('Potatoes', 'kg');
    }

    function materialAlreadyRegistered() {
        $this->given->IRegisteredTheMaterial_WithTheUnit('Potatoes', 'kg');
        $this->tryThat->IRegisterAMaterial_WithTheUnit('Potatoes', 'kg');
        $this->then->ItShouldFailWith('A material with the same name and unit is already registered.');
    }

    function sameNameDifferentUnit() {
        $this->given->IRegisteredTheMaterial_WithTheUnit('Oil', 'kg');
        $this->when->IRegisterAMaterial_WithTheUnit('Oil', 'liter');
        $this->then->AMaterial_WithTheUnit_ShouldBeRegistered('Oil', 'liter');
    }
}