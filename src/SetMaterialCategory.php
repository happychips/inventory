<?php
namespace happy\inventory;

use happy\inventory\app\Command;
use happy\inventory\model\MaterialIdentifier;

class SetMaterialCategory extends Command {

    /** @var MaterialIdentifier */
    private $material;
    /** @var string */
    private $category;

    /**
     * @param MaterialIdentifier $material
     * @param string $category
     * @param \DateTimeImmutable $when
     */
    public function __construct($material, $category, \DateTimeImmutable $when = null) {
        parent::__construct($when);
        $this->material = $material;
        $this->category = trim(strtolower($category));
    }

    /**
     * @return MaterialIdentifier
     */
    public function getMaterial() {
        return $this->material;
    }

    /**
     * @return string
     */
    public function getCategory() {
        return $this->category;
    }
}