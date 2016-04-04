<?php
namespace spec\happy\inventory\scenario;

use happy\inventory\app\Application;
use rtens\scrut\failures\IncompleteTestFailure;
use rtens\scrut\fixtures\ExceptionFixture;
use watoki\karma\stores\EventStore;
use watoki\karma\testing\Specification as KarmaSpecification;

/**
 * @property Context given
 * @property Action when
 * @property Action tryThat
 * @property Outcome then
 */
class Specification {

    /**
     * @param ExceptionFixture $try <-
     */
    public function __construct(ExceptionFixture $try) {
        $session = new FakeSession();
        $karma = new KarmaSpecification(function (EventStore $store) use ($session) {
            return new Application($store, $session);
        });

        $this->given = new Context($karma, $session);
        $this->when = new Action($karma);
        $this->tryThat = new Experiment($this->when, $try);
        $this->then = new Outcome($karma, $try);
    }

    protected function skip() {
        throw new IncompleteTestFailure('Skipped');
    }
}