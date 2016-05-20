<?php
namespace happy\inventory\app;

use happy\inventory\ConsumeMaterial;
use rtens\domin\delivery\web\Element;
use rtens\domin\delivery\web\WebRenderer;

class ConsumeMaterialRenderer implements WebRenderer {

    /** @var string[] */
    private $materials;

    /**
     * @param string[] $materials
     */
    public function __construct(array $materials) {
        $this->materials = $materials;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function handles($value) {
        return $value instanceof ConsumeMaterial;
    }

    /**
     * @param ConsumeMaterial $value
     * @return string
     */
    public function render($value) {
        return $value->getAmount() . ' of ' . $this->materials[(string)$value->getMaterial()];
    }

    /**
     * @param mixed $value
     * @return array|Element[]
     */
    public function headElements($value) {
        return [];
    }
}