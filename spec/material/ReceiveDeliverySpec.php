<?php
namespace spec\happy\inventory\material;
use spec\happy\inventory\scenario\Specification;

/**
 * Acquired Materials are added to the inventory when delivered.
 */
class ReceiveDeliverySpec extends Specification {

    function before() {
        $this->given->IAmLoggedInAs('test');
    }

    function exactDelivery() {
        $this->when->IReceiveTheDeliveryOf('acquisition1');
        $this->then->_ShouldBeReceived('acquisition1');
    }

    function withDocuments() {
        $this->when->IReceiveTheDeliveryOf_WithTheDocuments_Attached('acquisition1', ['receipt.pdf']);
        $this->then->_ShouldBeReceivedWithTheDocuments_Attached('acquisition1', ['receipt.pdf']);
    }

    function withExtraCosts() {
        $this->when->IReceiveTheDeliveryOf_WithTheExtraCostOf__For('acquisition1', 5, 'BTN', 'import tax');
        $this->then->_ShouldBeReceivedWithTheExtraCostOf__For('acquisition1', 5, 'BTN', 'import tax');
    }

    function differentQuantity() {
        $this->when->IReceiveTheDeliveryOf_Containing_Units('acquisition1', 10);
        $this->then->_ShouldBeReceivedContaining_Units('acquisition1', 10);
    }
}