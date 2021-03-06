<?php
namespace spec\happy\inventory;

use rtens\scrut\fixtures\ExceptionFixture;
use spec\happy\inventory\scenario\Action;
use spec\happy\inventory\scenario\Specification;

/**
 * The user that caused an event and the time when it was caused are always recorded
 */
class RecordUserAndTimeSpec extends Specification {

    /** @var Action */
    private $action;

    /**
     * @param ExceptionFixture $try <-
     */
    public function __construct(ExceptionFixture $try) {
        parent::__construct($try);
        $this->action = $this;
    }

    private function actions() {
        return [
            $this->action->IRegisterAMaterial_WithTheUnit('foo', 'bar'),
            $this->action->IAcquire_UnitsOf_For(1, 'foo', 1, 'bar'),
            $this->action->IReceiveTheDeliveryOf('foo'),
            $this->action->IConsume_UnitsOf(1, 'foo'),
            $this->action->IUpdateTheInventoryOf_To_Units(1, 'foo'),
            $this->action->IRegisterAProduct_WithTheUnit('foo', 'bar'),
            $this->action->IProduce_UnitsOf(1, 'foo'),
            $this->action->IUpdateTheStockOf_To_Units('foo', 1),
            $this->action->IDeliver_UnitsOf_To(1, 'foo', 'bar')
        ];
    }

    function requireBeingLoggedIn() {
        $this->forEachAction(function ($action, $args) {
            call_user_func_array([$this->tryThat, $action], $args);
            $this->then->ItShouldFailWith('Access denied.');
        });
    }

    function succeed() {
        $this->forEachAction(function ($action, $args) {
            $this->given->IAmLoggedInAs('Foo');
            $this->given->NowIs('2011-12-13 14:15:16 UTC');

            call_user_func_array([$this->when, $action], $args);
            $this->then->AllEventsShouldBeCausedBy('Foo');
            $this->then->AllEventsShouldHaveHappenedAt('2011-12-13 14:15:16 UTC');
        });
    }

    function overwriteTime() {
        $this->forEachAction(function ($action, $args) {
            $this->given->IAmLoggedInAs('Foo');
            $this->given->NowIs('2011-12-13 14:15:16 UTC');

            $this->when->IDateTheEventAt('2015-12-11 14:15:16 UTC');
            call_user_func_array([$this->when, $action], $args);
            $this->then->AllEventsShouldHaveHappenedAt('2011-12-13 14:15:16 UTC');
            $this->then->AllEventsShouldBeDatedAt('2015-12-11 14:15:16 UTC');
        });
    }

    function __call($name, $arguments) {
        return [$name, $arguments];
    }

    private function forEachAction(callable $do) {
        foreach ($this->actions() as list($action, $args)) {
            try {
                $do($action, $args);
            } catch (\Exception $e) {
                throw new \Exception("Action $action failed: " . $e->getMessage());
            }
        }
    }


}