<?php
namespace spec\happy\inventory\material;

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
        $this->then->Material_ShouldHaveTheCaption('Potatoes_kg', 'Potatoes (kg)');
    }

    function sortByName() {
        $this->given->IRegisteredTheMaterial_WithTheUnit('Potatoes', 'kg');
        $this->given->IRegisteredTheMaterial_WithTheUnit('Tomatoes', 'kg');
        $this->given->IRegisteredTheMaterial_WithTheUnit('Carrots', 'kg');
        $this->when->IListAllMaterials();
        $this->then->ItSholdList_Materials(3);
        $this->then->Material_ShouldBe(1, 'Carrots_kg');
        $this->then->Material_ShouldBe(2, 'Potatoes_kg');
        $this->then->Material_ShouldBe(3, 'Tomatoes_kg');
    }
}