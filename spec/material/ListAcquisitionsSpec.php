<?php
namespace spec\happy\inventory\material;

use spec\happy\inventory\scenario\Specification;

class ListAcquisitionsSpec extends Specification {

    function noAcquisitions() {
        $this->when->IListAllAcquisitions();
        $this->then->ItShouldList_Acquisitions(0);
    }

    function oneAcquisition() {
        $this->given->NowIs('2011-01-01');
        $this->given->IAcquired_Of(42, 'Potatoes');
        $this->when->IListAllAcquisitions();
        $this->then->ItShouldList_Acquisitions(1);
        $this->then->Acquisition_ShouldHaveTheCaption('42Potatoes', '2011-01-01 - Potatoes (42)');
    }

    function receivedDelivery() {
        $this->given->IAcquired_Of(42, 'Potatoes');
        $this->given->IAcquired_Of(12, 'Tomatoes');
        $this->given->IReceivedTheDeliveryOf(42, 'Potatoes');
        $this->when->IListAllAcquisitions();
        $this->then->ItShouldList_Acquisitions(1);
        $this->then->Acquisition_ShouldBe(1, '12Tomatoes');
    }

    function sortByDate() {
        $this->given->NowIs('2013-01-01');
        $this->given->IAcquired_Of(3, 'Potatoes');
        $this->given->NowIs('2011-01-01');
        $this->given->IAcquired_Of(1, 'Tomatoes');
        $this->given->NowIs('2012-01-01');
        $this->given->IAcquired_Of(2, 'Carrots');

        $this->when->IListAllAcquisitions();
        $this->then->ItShouldList_Acquisitions(3);
        $this->then->Acquisition_ShouldBe(1, '1Tomatoes');
        $this->then->Acquisition_ShouldBe(2, '2Carrots');
        $this->then->Acquisition_ShouldBe(3, '3Potatoes');
    }
}