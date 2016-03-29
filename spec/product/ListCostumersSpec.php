<?php
namespace spec\happy\inventory\product;

use spec\happy\inventory\scenario\Specification;

class ListCostumersSpec extends Specification {

    function noCostumer() {
        $this->when->IListAllCostumers();
        $this->then->ThereShouldBe_Costumers(0);
    }

    function oneCostumer() {
        $this->given->IAddedACostumer('8/11');
        $this->when->IListAllCostumers();
        $this->then->ThereShouldBe_Costumers(1);
    }

    function orderByName() {
        $this->given->IAddedACostumer('8/11');
        $this->given->IAddedACostumer('6/11');
        $this->given->IAddedACostumer('7/11');
        $this->when->IListAllCostumers();
        $this->then->ThereShouldBe_Costumers(3);
        $this->then->Costumer_ShouldBe(1, '6/11');
        $this->then->Costumer_ShouldBe(2, '7/11');
        $this->then->Costumer_ShouldBe(3, '8/11');
    }
}