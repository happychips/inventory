<?php
namespace happy\inventory\app;

use happy\inventory\ListAcquisitions;
use happy\inventory\ListMaterials;
use happy\inventory\model\Inventory;
use happy\inventory\model\Session;
use happy\inventory\projecting\AcquisitionList;
use happy\inventory\projecting\EventHistory;
use happy\inventory\projecting\MaterialList;
use happy\inventory\ShowHistory;
use watoki\karma\command\AggregateFactory;
use watoki\karma\command\CommandHandler;
use watoki\karma\EventStore;
use watoki\karma\query\ProjectionFactory;
use watoki\karma\query\QueryProjector;

class Application implements AggregateFactory, ProjectionFactory {
    /** @var Session */
    private $session;
    /** @var CommandHandler */
    private $command;
    /** @var QueryProjector */
    private $query;
    /** @var EventStore */
    private $events;

    /**
     * @param EventStore $events
     */
    public function __construct(EventStore $events, Session $session) {
        $this->events = $events;
        $this->session = $session;
        $this->command = new CommandHandler($events, $this);
        $this->query = new QueryProjector($events, $this);
    }

    public function handle($commandOrQuery) {
        if ($commandOrQuery instanceof Command) {
            $this->command->handle($commandOrQuery);
            return null;
        } else {
            return $this->query->project($commandOrQuery);
        }
    }

    /**
     * @param object $command
     * @return string
     */
    public function handleMethod($command) {
        return 'handle' . (new \ReflectionClass($command))->getShortName();
    }

    /**
     * @param object $event
     * @return string
     */
    public function applyMethod($event) {
        return 'apply' . (new \ReflectionClass($event))->getShortName();
    }

    /**
     * @param object $command
     * @return mixed
     */
    public function getAggregateIdentifier($command) {
        return Inventory::IDENTIFIER;
    }

    /**
     * @param mixed $identifier
     * @return object
     */
    public function buildAggregateRoot($identifier) {
        return new Inventory($this->session);
    }

    /**
     * @param object $query
     * @return object
     * @throws \Exception
     */
    public function buildProjection($query) {
        if ($query instanceof ListMaterials) {
            return new MaterialList();
        } else if ($query instanceof ShowHistory) {
            return new EventHistory($this->events->allEvents());
        } else if ($query instanceof ListAcquisitions) {
            return new AcquisitionList();
        }

        throw new \Exception('Unknown query');
    }
}