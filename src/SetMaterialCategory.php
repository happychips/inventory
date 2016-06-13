<?php
namespace happy\inventory;

use happy\inventory\app\Command;
use happy\inventory\model\MaterialIdentifier;

class SetMaterialCategory extends Command {

    /** @var MaterialIdentifier[] */
    private $materials;
    /** @var string */
    private $category;

    /**
     * @param string $category
     * @param MaterialIdentifier[] $materials
     * @param \DateTimeImmutable $when
     */
    public function __construct($category, array $materials, \DateTimeImmutable $when = null) {
        parent::__construct($when);
        $this->materials = $materials;
        $this->category = trim(strtolower($category));
    }

    /**
     * @return MaterialIdentifier[]
     */
    public function getMaterials() {
        return $this->materials;
    }

    /**
     * @return string
     */
    public function getCategory() {
        return $this->category;
    }
}