<?php
namespace happy\inventory\material;
use happy\inventory\scenario\Specification;

/**
 * A Material defines the name and unit of something that can be added to the inventory.
 */
class RegisterMaterialSpec extends Specification {

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
        $this->then->AnArticle_WithTheUnit_ShouldBeRegistered('foo', 'bar');
    }
}