<?php
namespace spec\happy\inventory\material;

use rtens\scrut\Failure;
use spec\happy\inventory\scenario\Specification;

class ListMaterialsSpec extends Specification {

    function noMaterials() {
        $this->when->IListAllMaterials();
        $this->then->ItSholdList_Materials(0);
    }

    function oneMaterial() {
        $this->given->IRegisteredTheMaterial_WithTheUnit('Potatoes', 'kg');
        $this->when->IListAllMaterials();
        $this->then->ItSholdList_Materials(1);
        $this->then->Material_ShouldHaveTheCaption('Potatoes', 'Potatoes (kg)');
    }

    function sortByName() {
        throw new Failure('Incomplete');
    }
}