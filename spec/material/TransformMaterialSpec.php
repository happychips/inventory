<?php
namespace spec\happy\inventory\material;

use spec\happy\inventory\scenario\Specification;

/**
 * Consumes a quantity of one Material and acquires quantities of several different Materials
 */
class TransformMaterialSpec extends Specification {

    function inputCannotBeEmpty() {
        $this->tryThat->ITransform_Into([], []);
        $this->then->ItShouldFailWith('Input cannot be empty.');
    }

    function outputCannotBeEmpty() {
        $this->tryThat->ITransform_Into([[1, 'kg', 'Rice']], []);
        $this->then->ItShouldFailWith('Output cannot be empty.');
    }

    function oneInOneOut() {
        $this->given->theNextGeneratedIdentifierIs('foo');
        $this->given->IAmLoggedInAs('test');

        $this->when->ITransform_Into([[30, 'kg', 'Potatoes']], [[20, 'kg', 'Mashed Potatoes']]);
        $this->then->_UnitsOf_ShouldBeConsumed(30, 'Potatoes');
        $this->then->_UnitsOf_For__ShouldBeAcquired(20, 'Mashed Potatoes', 0, 'BTN');
        $this->then->_ShouldBeReceived('foo');
    }
}