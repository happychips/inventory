<?php
namespace happy\inventory\events;

use happy\inventory\model\Time;
use happy\inventory\model\UserIdentifier;

abstract class Event {

    /** @var UserIdentifier */
    private $who;
    /** @var \DateTimeImmutable */
    private $when;

    /**
     * @param UserIdentifier $who
     * @param \DateTimeImmutable|null $when
     */
    public function __construct(UserIdentifier $who, \DateTimeImmutable $when = null) {
        $this->who = $who;
        $this->when = $when ?: Time::now();
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
}