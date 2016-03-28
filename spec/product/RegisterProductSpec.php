<?php
namespace spec\happy\inventory\product;
use spec\happy\inventory\scenario\Specification;

/**
 * A product defines what can be in stock and in what unit
 */
class RegisterProductSpec extends Specification {

    function nameCannotBeEmpty() {
        $this->skip();
        $this->tryThat->IRegisterAProduct_WithTheUnit("  \t ", 'pack');
        $this->then->ItShouldFailWith('The product name cannot be empty');
    }

    function unitCannotBeEmpty() {
        $this->skip();
        $this->tryThat->IRegisterAProduct_WithTheUnit('Chips', " \t ");
        $this->then->ItShouldFailWith('The unit cannot be empty');
    }

    function succeed() {
        $this->when->IRegisterAProduct_WithTheUnit('Chips', 'pack');
        $this->then->AProduct_WithTheUnit_ShouldBeRegistered('foo', 'bar');
    }
}