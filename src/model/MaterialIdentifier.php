<?php
namespace happy\inventory\model;

class MaterialIdentifier extends Identifier {

    public static function fromNameAndUnit($name, $unit) {
        return new MaterialIdentifier($name . '_' . $unit);
    }
}