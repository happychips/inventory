<?php
namespace spec\happy\inventory\product;

use spec\happy\inventory\scenario\Specification;

class ListProductsSpec extends Specification {

    function noProducts() {
        $this->when->IListAllProducts();
        $this->then->ItShouldList_Products(0);
    }

    function oneProduct() {
        $this->given->IRegisteredTheProduct_WithTheUnit('Chips', 'pack');
        $this->when->IListAllProducts();
        $this->then->ItShouldList_Products(1);
        $this->then->Product_ShouldHaveTheCaption('Chips_pack', 'Chips (pack)');
    }

    function orderByName() {
        $this->given->IRegisteredTheProduct_WithTheUnit('Chips', 'b');
        $this->given->IRegisteredTheProduct_WithTheUnit('Other', 'a');
        $this->given->IRegisteredTheProduct_WithTheUnit('Chips', 'a');
        $this->when->IListAllProducts();
        $this->then->ItShouldList_Products(3);
        $this->then->Product_ShouldBe(1, 'Chips_a');
        $this->then->Product_ShouldBe(2, 'Chips_b');
        $this->then->Product_ShouldBe(3, 'Other_a');
    }
}