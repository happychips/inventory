<?php
namespace spec\happy\inventory\scenario;

use rtens\scrut\fixtures\ExceptionFixture;

class Experiment {

    /** @var Action */
    private $action;
    /** @var ExceptionFixture */
    private $try;

    /**
     * @param Action $action
     * @param ExceptionFixture $try
     */
    public function __construct(Action $action, ExceptionFixture $try) {
        $this->action = $action;
        $this->try = $try;
    }

    function __call($name, $arguments) {
        $this->try->tryTo(function () use ($name, $arguments) {
            call_user_func_array([$this->action, $name], $arguments);
        });
    }
}