<?php
namespace happy\inventory;

use happy\inventory\model\Inventory;
use watoki\karma\command\AggregateFactory;
use watoki\karma\command\CommandHandler;
use watoki\karma\EventStore;

class Application implements AggregateFactory {

    /** @var CommandHandler */
    private $command;

    /**
     * @param EventStore $events
     */
    public function __construct(EventStore $events) {
        $this->command = new CommandHandler($events, $this);
    }

    public function handle($commandOrQuery) {
        $this->command->handle($commandOrQuery);
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
        return 'foo';
    }

    /**
     * @param mixed $identifier
     * @return object
     */
    public function buildAggregateRoot($identifier) {
        return new Inventory();
    }
}