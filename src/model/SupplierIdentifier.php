<?php
namespace happy\inventory\model;

class SupplierIdentifier extends Identifier {

    public static function fromName($name) {
        return new SupplierIdentifier($name);
    }
}