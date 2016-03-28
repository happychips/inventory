<?php
namespace happy\inventory\model;

class AcquisitionIdentifier extends Identifier {

    public static function generate() {
        return new AcquisitionIdentifier(date('YmdHis_') . substr(uniqid(), -4));
    }
}