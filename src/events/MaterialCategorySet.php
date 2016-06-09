<?php
namespace happy\inventory\events;

use happy\inventory\model\MaterialIdentifier;
use happy\inventory\model\UserIdentifier;

class MaterialCategorySet extends Event {

    /** @var MaterialIdentifier */
    private $material;
    /** @var string */
    private $category;

    /**
     * @param MaterialIdentifier $material
     * @param string $category
     * @param UserIdentifier $who
     */
    public function __construct(MaterialIdentifier $material, $category, UserIdentifier $who, \DateTimeImmutable $when = null) {
        parent::__construct($who, $when);
        $this->material = $material;
        $this->category = $category ;
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