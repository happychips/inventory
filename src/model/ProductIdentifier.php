<?php
namespace happy\inventory\model;

class ProductIdentifier extends Identifier {

    public static function fromNameAndUnit($name, $unit) {
        return new ProductIdentifier($name . '_' . $unit);
    }
}