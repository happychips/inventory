<?php
namespace happy\inventory\model;

class CostumerIdentifier extends Identifier {

    public static function fromName($name) {
        return new CostumerIdentifier($name);
    }
}