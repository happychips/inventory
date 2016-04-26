<?php
namespace happy\inventory\events;

use happy\inventory\model\Time;
use happy\inventory\model\UserIdentifier;

abstract class Event {

    /** @var UserIdentifier */
    private $who;
    /** @var \DateTimeImmutable */
    private $when;
    /** @var \DateTimeImmutable|null */
    private $dated;

    /**
     * @param UserIdentifier $who
     * @param \DateTimeImmutable|null $dated
     */
    public function __construct(UserIdentifier $who, \DateTimeImmutable $dated = null) {
        $this->who = $who;
        $this->dated = $dated;
        $this->when = Time::now();
    }

    /**
     * @return UserIdentifier
     */
    public function getWho() {
        return $this->who;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getWhen() {
        return $this->when;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getDated() {
        return $this->dated;
    }
}