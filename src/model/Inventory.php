<?php
namespace happy\inventory\model;

use happy\inventory\AcquireMaterials;
use happy\inventory\AddCostumer;
use happy\inventory\AddSupplier;
use happy\inventory\ConsumeMaterial;
use happy\inventory\DeliverProduct;
use happy\inventory\events\CostumerAdded;
use happy\inventory\events\CostumerDetailsChanged;
use happy\inventory\events\DeliveryReceived;
use happy\inventory\events\InventoryUpdated;
use happy\inventory\events\LinkedConsumptionsSet;
use happy\inventory\events\MaterialCategorySet;
use happy\inventory\events\MaterialConsumed;
use happy\inventory\events\MaterialRegistered;
use happy\inventory\events\MaterialsAcquired;
use happy\inventory\events\ProductDelivered;
use happy\inventory\events\ProductProduced;
use happy\inventory\events\ProductRegistered;
use happy\inventory\events\StockUpdated;
use happy\inventory\events\SupplierAdded;
use happy\inventory\ProduceProduct;
use happy\inventory\ReceiveDelivery;
use happy\inventory\RegisterMaterial;
use happy\inventory\RegisterProduct;
use happy\inventory\SetLinkedConsumption;
use happy\inventory\SetMaterialCategory;
use happy\inventory\UpdateInventory;
use happy\inventory\UpdateStock;
use rtens\domin\parameters\File;
use rtens\domin\parameters\file\SavedFile;

class Inventory {

    const IDENTIFIER = 'inventory.json';

    /** @var Session */
    private $session;
    /** @var string */
    private $filesDir;
    /** @var MaterialIdentifier[] */
    private $materials = [];
    /** @var CostumerIdentifier[] */
    private $costumers = [];
    /** @var ProductIdentifier[] */
    private $products = [];
    /** @var SupplierIdentifier[] */
    private $suppliers = [];
    /** @var ConsumeMaterial[][] indexed by ProductIdentifier */
    private $linkedConsumptions = [];

    /**
     * @param Session $session
     * @param string $userDir
     */
    public function __construct(Session $session, $userDir) {
        $this->session = $session;
        $this->filesDir = $userDir . '/files';

        if (!file_exists($this->filesDir)) {
            mkdir($this->filesDir, 0777, true);
        }
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

    public function handleAcquireMaterials(AcquireMaterials $c) {
        $acquisition = AcquisitionIdentifier::generate();

        $events[] = new MaterialsAcquired(
            $acquisition,
            $c->getMaterials(),
            $c->getSupplier(),
            $this->saveFiles($c->getDocumentFiles()),
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
            $c->isPartialDelivery(),
            $c->getDeviantAmounts(),
            $this->saveFiles($c->getDocumentFiles()),
            $c->getExtraCosts(),
            $this->session->requireLogin(),
            $c->getWhen()
        );
    }

    /**
     * @param File[] $files
     * @return File[]
     */
    private function saveFiles($files) {
        $savedFiles = [];
        foreach ($files as $file) {
            $path = $this->filesDir . '/' . date('Ymd_His_') . substr(uniqid(), -4) . '_' . $file->getName();
            $file->save($path);

            $savedFiles[] = new SavedFile($path, $file->getName(), $file->getType());
        }

        return $savedFiles;
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

        $events[] = new CostumerAdded(
            $costumer,
            $c->getName(),
            $this->session->requireLogin(),
            $c->getWhen()
        );

        if ($c->getContact() || $c->getLocation()) {
            $events[] = new CostumerDetailsChanged(
                $costumer,
                $c->getContact(),
                $c->getLocation(),
                $this->session->requireLogin(),
                $c->getWhen()
            );
        }

        return $events;
    }

    public function applyCostumerAdded(CostumerAdded $e) {
        $this->costumers[] = $e->getCostumer();
    }

    public function handleAddSupplier(AddSupplier $c) {
        $supplier = SupplierIdentifier::fromName($c->getName());
        if (in_array($supplier, $this->suppliers)) {
            throw new \Exception('A supplier with that name was already added.');
        }
        return new SupplierAdded(
            $supplier,
            $c->getName(),
            $this->session->requireLogin(),
            $c->getWhen()
        );
    }

    public function applySupplierAdded(SupplierAdded $e) {
        $this->suppliers[] = $e->getSupplier();
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
        $events = [
            new ProductProduced(
                $c->getProduct(),
                $c->getAmount(),
                $this->session->requireLogin(),
                $c->getWhen()
            )
        ];

        foreach ($this->getLinkedConsumptions($c->getProduct()) as $consumption) {
            $events[] = new MaterialConsumed(
                $consumption->getMaterial(),
                $consumption->getAmount() * $c->getAmount(),
                $this->session->requireLogin(),
                $c->getWhen()
            );
        }

        return $events;
    }

    private function getLinkedConsumptions(ProductIdentifier $product) {
        if (!isset($this->linkedConsumptions[(string)$product])) {
            return [];
        }

        return $this->linkedConsumptions[(string)$product];
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

    public function handleSetLinkedConsumption(SetLinkedConsumption $c) {
        if (!in_array($c->getProduct(), $this->products)) {
            throw new \Exception('This product does not exist.');
        }

        foreach ($c->getConsumptions() as $consumption) {
            if (!in_array($consumption->getMaterial(), $this->materials)) {
                throw new \Exception("Material [{$consumption->getMaterial()}] does not exist.");
            }
        }

        return new LinkedConsumptionsSet(
            $c->getProduct(),
            $c->getConsumptions(),
            $this->session->requireLogin(),
            $c->getWhen()
        );
    }

    public function applyLinkedConsumptionsSet(LinkedConsumptionsSet $e) {
        $this->linkedConsumptions[(string)$e->getProduct()] = $e->getConsumptions();
    }

    public function handleSetMaterialCategory(SetMaterialCategory $c) {
        $events = [];
        foreach ($c->getMaterials() as $material) {
            $events[] = new MaterialCategorySet(
                $material,
                $c->getCategory(),
                $this->session->requireLogin(),
                $c->getWhen()
            );
        }
        return $events;
    }
}