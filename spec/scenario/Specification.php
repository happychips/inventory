<?php
namespace happy\inventory\scenario;

use rtens\scrut\Assert;
use watoki\karma\command\EventListener;
use watoki\karma\Specification as KarmaSpecification;

/**
 * @property Assert assert <-
 * @property Context given
 * @property Action when
 * @property Action tryThat
 * @property Outcome then
 */
class Specification extends KarmaSpecification {

    public function __construct() {
        parent::__construct();

        $this->given = new Context();
        $this->when = new Action();
        $this->tryThat = new Experiment();
        $this->then = new Outcome();
    }

    /**
     * @return EventListener[]
     */
    protected function listeners() {
        // TODO: Implement listeners() method.
    }

    /**
     * @param mixed $commandOrQuery
     * @return mixed
     */
    protected function handle($commandOrQuery) {
        // TODO: Implement handle() method.
    }
}