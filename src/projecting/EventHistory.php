<?php
namespace happy\inventory\projecting;

class EventHistory {

    /** @var mixed[] */
    private $events;

    /**
     * @param mixed[] $events
     */
    public function __construct($events) {
        $this->events = array_reverse($events);
    }

    /**
     * @return mixed[]
     */
    public function getEvents() {
        return $this->events;
    }
}