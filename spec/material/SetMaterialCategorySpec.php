<?php
namespace spec\happy\inventory\material;

use spec\happy\inventory\scenario\Specification;

class SetMaterialCategorySpec extends Specification {

    function before() {
        $this->given->IAmLoggedInAs('test');
    }

    function success() {
        $this->when->IPut_Into('Tomatoes', 'fruits');
        $this->then->Material_ShouldHaveTheCategory('Tomatoes', 'fruits');
    }

    function multiple() {
        $this->when->IPut_Into(['Potatoes', 'Tomatoes'], 'vegetables');
        $this->then->Material_ShouldHaveTheCategory('Tomatoes', 'vegetables');
        $this->then->Material_ShouldHaveTheCategory('Potatoes', 'vegetables');
    }

    function normalizeCategory() {
        $this->when->IPut_Into('Tomatoes', '    Fruits  ');
        $this->then->Material_ShouldHaveTheCategory('Tomatoes', 'fruits');
    }
}