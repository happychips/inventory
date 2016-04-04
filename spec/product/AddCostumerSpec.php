<?php
namespace spec\happy\inventory\product;

use spec\happy\inventory\scenario\Specification;

/**
 * Products are sold and delivered
 */
class AddCostumerSpec extends Specification {

    function before() {
        $this->given->IAmLoggedInAs('nobody');
    }

    function succeed() {
        $this->when->IAddTheCostumer('Shop No 7');
        $this->then->TheCostumer_ShouldBeAdded('Shop No 7');
    }

    function withDetails() {
        $this->when->IAddTheCostumer_WithContact_AndLocation('Shop No 7', '177123456', 'Thimphu');
        $this->then->TheContactOfCostumer_ShouldBeChangedTo('Shop No 7', '177123456');
        $this->then->TheLocationOfCostumer_ShouldBeChangedTo('Shop No 7', 'Thimphu');
    }

    function costumerAlreadyAdded() {
        $this->given->IAddedACostumer('8/11');
        $this->tryThat->IAddTheCostumer('8/11');
        $this->then->ItShouldFailWith('A costumer with that name was already added.');
    }
}