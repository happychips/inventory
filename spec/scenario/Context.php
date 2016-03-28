<?php
namespace spec\happy\inventory\scenario;

use happy\inventory\model\Time;
use happy\inventory\model\UserIdentifier;
use watoki\karma\Specification as KarmaSpecification;

class Context {

    /** @var KarmaSpecification */
    public $karma;
    /** @var FakeSession */
    private $session;

    /**
     * @param KarmaSpecification $karma
     * @param FakeSession $session
     */
    public function __construct(KarmaSpecification $karma, FakeSession $session) {
        $this->session = $session;
        $this->karma = $karma;
    }

    public function IAmLoggedInAs($user) {
        $this->session->login(new UserIdentifier($user));
    }

    public function NowIs($when) {
        Time::freeze(new \DateTimeImmutable($when));
    }

    public function nothingHasHappened() {
        $this->karma->reset();
    }
}