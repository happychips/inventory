<?php
namespace spec\happy\inventory\reporting;

use spec\happy\inventory\scenario\Specification;

class ShowStockSpec extends Specification {

    function noProducts() {
        $this->when->IShowTheStock();
        $this->then->TheStockShouldContain_Products(0);
    }

    function noCounts() {
        $this->given->IRegisteredTheProduct_WithTheUnit('Chips', 'packs');
        $this->when->IShowTheStock();
        $this->then->TheStockShouldContain_Products(1);
        $this->then->Product_InStockShouldHaveTheCaption(1, 'Chips (packs)');
    }

    function sortByName() {
        $this->given->IRegisteredTheProduct_WithTheUnit('Stuff', 'foo');
        $this->given->IRegisteredTheProduct_WithTheUnit('Chips', 'packs');
        $this->given->IRegisteredTheProduct_WithTheUnit('More', 'that');

        $this->when->IShowTheStock();
        $this->then->TheStockShouldContain_Products(3);
        $this->then->Product_InStockShouldHaveTheCaption(1, 'Chips (packs)');
        $this->then->Product_InStockShouldHaveTheCaption(2, 'More (that)');
        $this->then->Product_InStockShouldHaveTheCaption(3, 'Stuff (foo)');
    }

    function productProduced() {
        $this->given->IRegisteredTheProduct_WithTheUnit('Chips', 'packs');
        $this->given->IProduced__Of(5, 'packs', 'Chips');

        $this->when->IShowTheStock();
        $this->then->Product_InStockShouldCount(1, 5);
    }

    function multipleProduced() {
        $this->given->IRegisteredTheProduct_WithTheUnit('Chips', 'packs');
        $this->given->IProduced__Of(5, 'packs', 'Chips');
        $this->given->IProduced__Of(3, 'packs', 'Chips');

        $this->when->IShowTheStock();
        $this->then->Product_InStockShouldCount(1, 8);
    }

    function productDelivered() {
        $this->given->IRegisteredTheProduct_WithTheUnit('Chips', 'packs');
        $this->given->IProduced__Of(7, 'packs', 'Chips');
        $this->given->IDelivered__Of(3, 'packs', 'Chips');

        $this->when->IShowTheStock();
        $this->then->Product_InStockShouldCount(1, 4);
    }

    function stockUpdated() {
        $this->given->IRegisteredTheProduct_WithTheUnit('Chips', 'packs');
        $this->given->IProduced__Of(7, 'packs', 'Chips');
        $this->given->IDelivered__Of(3, 'packs', 'Chips');
        $this->given->IUpdatedTheStockOf_To('Chips', 9, 'packs');

        $this->when->IShowTheStock();
        $this->then->Product_InStockShouldCount(1, 9);
    }
}