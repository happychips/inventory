<?php
namespace happy\inventory\app;

use happy\inventory\ListAcquisitions;
use happy\inventory\ListCostumers;
use happy\inventory\ListLinkedConsumptions;
use happy\inventory\ListLinkedProductConsumptions;
use happy\inventory\ListMaterials;
use happy\inventory\ListProducts;
use happy\inventory\ListSuppliers;
use happy\inventory\model\Inventory;
use happy\inventory\model\Session;
use happy\inventory\projecting\AcquisitionList;
use happy\inventory\projecting\CostumerList;
use happy\inventory\projecting\CurrentInventory;
use happy\inventory\projecting\CurrentStock;
use happy\inventory\projecting\EventHistory;
use happy\inventory\projecting\LinkedConsumptions;
use happy\inventory\projecting\LinkedProductConsumptions;
use happy\inventory\projecting\MaterialList;
use happy\inventory\projecting\ProductList;
use happy\inventory\projecting\SupplierList;
use happy\inventory\ShowHistory;
use happy\inventory\ShowInventory;
use happy\inventory\ShowStock;
use watoki\karma\Application as Karma;
use watoki\karma\implementations\aggregates\ObjectAggregateFactory;
use watoki\karma\implementations\projections\ObjectProjectionFactory;
use watoki\karma\stores\EventStore;

class Application extends Karma {

    /** @var Session */
    private $session;
    /** @var EventStore */
    private $events;
    /** @var string */
    private $userDir;

    /**
     * @param EventStore $events
     * @param Session $session
     * @param string $userDir
     */
    public function __construct(EventStore $events, Session $session, $userDir) {
        parent::__construct($events,
            (new ObjectAggregateFactory([$this, 'buildAggregateRoot']))
                ->setGetAggregateIdentifierCallback([$this, 'getAggregateIdentifier']),
            new ObjectProjectionFactory([$this, 'buildProjection']));
        $this->session = $session;
        $this->events = $events;
        $this->userDir = $userDir;
    }

    /**
     * @return mixed
     */
    public function getAggregateIdentifier() {
        return Inventory::IDENTIFIER;
    }

    /**
     * @return object
     */
    public function buildAggregateRoot() {
        return new Inventory($this->session, $this->userDir);
    }

    /**
     * @param object $query
     * @return object
     * @throws \Exception
     */
    public function buildProjection($query = null) {
        if ($query instanceof ListMaterials) {
            return new MaterialList();
        } else if ($query instanceof ShowHistory) {
            return new EventHistory($this->events->allEvents());
        } else if ($query instanceof ListAcquisitions) {
            return new AcquisitionList();
        } else if ($query instanceof ListProducts) {
            return new ProductList();
        } else if ($query instanceof ListCostumers) {
            return new CostumerList();
        } else if ($query instanceof ListSuppliers) {
            return new SupplierList();
        } else if ($query instanceof ShowInventory) {
            return new CurrentInventory();
        } else if ($query instanceof ShowStock) {
            return new CurrentStock();
        } else if ($query instanceof ListLinkedConsumptions) {
            return new LinkedConsumptions();
        } else if ($query instanceof ListLinkedProductConsumptions) {
            return new LinkedProductConsumptions($query->getProduct());
        }

        throw new \Exception('Unknown query');
    }

    /**
     * @param mixed $commandOrQuery
     * @return boolean
     */
    protected function isCommand($commandOrQuery) {
        return $commandOrQuery instanceof Command;
    }
}