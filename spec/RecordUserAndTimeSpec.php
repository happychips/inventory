<?php
namespace spec\happy\inventory;

use spec\happy\inventory\scenario\Specification;

/**
 * The user that caused an event and the time when it was caused are always recorded
 */
class RecordUserAndTimeSpec extends Specification {

    private static $actions = [];

    function requireBeingLoggedIn() {
        foreach (self::$actions as $action => $args) {
            call_user_func_array([$this->tryThat, $action], $args);
            $this->then->ItShouldFailWith('Access denied.');
        }
    }

    function succeed() {
        $this->given->IAmLoggedInAs('Foo');
        $this->given->NowIs('2011-12-13 14:15:16 UTC');

        foreach (self::$actions as $action => $args) {
            call_user_func_array([$this->when, $action], $args);
            $this->then->AllEventsShouldHaveHappenedAt('2011-12-13 14:15:16 UTC');
            $this->then->AllEventsShouldBeCausedBy('Foo');
        }
    }

    function overwriteTime() {
        $this->given->IAmLoggedInAs('Foo');
        $this->given->NowIs('2011-12-13 14:15:16 UTC');

        foreach (self::$actions as $action => $args) {
            $args['when'] = new \DateTimeImmutable('2015-12-11 14:15:16');

            call_user_func_array([$this->when, $action], $args);
            $this->then->AllEventsShouldHaveHappenedAt('2015-12-11 14:15:16');
        }
    }
}