<?php
namespace spec\happy\inventory\scenario;

use happy\inventory\Application;
use rtens\scrut\failures\IncompleteTestFailure;
use rtens\scrut\fixtures\ExceptionFixture;
use watoki\karma\command\EventListener;
use watoki\karma\EventStore;
use watoki\karma\Specification as KarmaSpecification;

/**
 * @property Context given
 * @property Action when
 * @property Action tryThat
 * @property Outcome then
 */
class Specification extends KarmaSpecification {

    /**
     * @param ExceptionFixture $try <-
     */
    public function __construct(ExceptionFixture $try) {
        parent::__construct();

        $this->given = new Context();
        $this->when = new Action($this);
        $this->tryThat = new Experiment($this->when, $try);
        $this->then = new Outcome($this, $try);
    }

    /**
     * @return EventListener[]
     */
    protected function listeners() {
    }

    /**
     * @param EventStore $events
     * @param mixed $commandOrQuery
     * @return mixed
     */
    protected function handle(EventStore $events, $commandOrQuery) {
        (new Application($events))->handle($commandOrQuery);
    }

    protected function skip() {
        throw new IncompleteTestFailure('Skipped');
    }
}