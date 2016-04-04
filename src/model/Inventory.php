<?php
namespace happy\inventory\model;

use happy\inventory\AcquireMaterial;
use happy\inventory\AddCostumer;
use happy\inventory\ConsumeMaterial;
use happy\inventory\DeliverProduct;
use happy\inventory\events\CostumerAdded;
use happy\inventory\events\DeliveryReceived;
use happy\inventory\events\InventoryUpdated;
use happy\inventory\events\MaterialAcquired;
use happy\inventory\events\MaterialConsumed;
use happy\inventory\events\MaterialRegistered;
use happy\inventory\events\ProductDelivered;
use happy\inventory\events\ProductProduced;
use happy\inventory\events\ProductRegistered;
use happy\inventory\events\StockUpdated;
use happy\inventory\ProduceProduct;
use happy\inventory\ReceiveDelivery;
use happy\inventory\RegisterMaterial;
use happy\inventory\RegisterProduct;
use happy\inventory\UpdateInventory;
use happy\inventory\UpdateStock;

class Inventory {

    const IDENTIFIER = 'inventory.json';

    /** @var Session */
    private $session;
    /** @var MaterialIdentifier[] */
    private $materials = [];
    /** @var CostumerIdentifier[] */
    private $costumers = [];
    /** @var ProductIdentifier[] */
    private $products = [];

    /**
     * @param Session $session
     */
    public function __construct(Session $session) {
        $this->session = $session;
    }

    public function handleRegisterMaterial(RegisterMaterial $c) {
        $material = MaterialIdentifier::fromNameAndUnit($c->getName(), $c->getUnit());

        if (in_array($material, $this->materials)) {
            throw new \Exception('A material with the same name and unit is already registered.');
        }

        return new MaterialRegistered(
            $material,
            $c->getName(),
            $c->getUnit(),
            $this->session->requireLogin(),
            $c->getWhen());
    }

    public function applyMaterialRegistered(MaterialRegistered $e) {
        $this->materials[] = $e->getMaterial();
    }

    public function handleAcquireMaterial(AcquireMaterial $c) {
        $acquisition = AcquisitionIdentifier::generate();

        $events[] = new MaterialAcquired(
            $acquisition,
            $c->getMaterial(),
            $c->getAmount(),
            $c->getCost(),
            $c->getDocuments(),
            $this->session->requireLogin(),
            $c->getWhen());

        if ($c->isAlreadyReceived()) {
            $events[] = $this->handleReceiveDelivery(new ReceiveDelivery(
                $acquisition
            ));
        }

        return $events;
    }

    public function handleReceiveDelivery(ReceiveDelivery $c) {
        return new DeliveryReceived(
            $c->getAcquisition(),
            $c->getAmount(),
            $c->getDocuments(),
            $c->getExtraCosts(),
            $this->session->requireLogin(),
            $c->getWhen()
        );
    }

    public function handleConsumeMaterial(ConsumeMaterial $c) {
        return new MaterialConsumed(
            $c->getMaterial(),
            $c->getAmount(),
            $this->session->requireLogin(),
            $c->getWhen()
        );
    }

    public function handleUpdateInventory(UpdateInventory $c) {
        return new InventoryUpdated(
            $c->getMaterial(),
            $c->getAmount(),
            $this->session->requireLogin(),
            $c->getWhen()
        );
    }

    public function handleAddCostumer(AddCostumer $c) {
        $costumer = CostumerIdentifier::fromName($c->getName());
        if (in_array($costumer, $this->costumers)) {
            throw new \Exception('A costumer with that name was already added.');
        }
        return new CostumerAdded(
            $costumer,
            $c->getName(),
            $this->session->requireLogin()
        );
    }

    public function applyCostumerAdded(CostumerAdded $e) {
        $this->costumers[] = $e->getCostumer();
    }

    public function handleRegisterProduct(RegisterProduct $c) {
        $product = ProductIdentifier::fromNameAndUnit($c->getName(), $c->getUnit());
        if (in_array($product, $this->products)) {
            throw new \Exception('A product with the same name and unit is already registered.');
        }
        return new ProductRegistered(
            $product,
            $c->getName(),
            $c->getUnit(),
            $this->session->requireLogin(),
            $c->getWhen()
        );
    }

    public function applyProductRegistered(ProductRegistered $e) {
        $this->products[] = $e->getProduct();
    }

    public function handleProduceProduct(ProduceProduct $c) {
        return new ProductProduced(
            $c->getProduct(),
            $c->getAmount(),
            $this->session->requireLogin(),
            $c->getWhen()
        );
    }

    public function handleUpdateStock(UpdateStock $c) {
        return new StockUpdated(
            $c->getProduct(),
            $c->getAmount(),
            $this->session->requireLogin(),
            $c->getWhen()
        );
    }

    public function handleDeliverProduct(DeliverProduct $c) {
        return new ProductDelivered(
            $c->getProduct(),
            $c->getAmount(),
            $c->getCostumer(),
            $this->session->requireLogin(),
            $c->getWhen()
        );
    }
}