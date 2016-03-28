<?php
namespace happy\inventory\app;

class Command {

    /** @var \DateTimeImmutable|null */
    private $when;

    /**
     * @param \DateTimeImmutable|null $when
     */
    public function __construct(\DateTimeImmutable $when = null) {
        $this->when = $when;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getWhen() {
        return $this->when;
    }
}