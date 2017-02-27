<?php

namespace CultuurNet\UDB3\IIS\Silex\Event;

use CultuurNet\UDB3\IISStore\Stores\RepositoryInterface;

class EventControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    /**
     * @var EventController
     */
    private $eventController;

    protected function setUp()
    {
        $this->repository = $this->createMock(RepositoryInterface::class);

        $this->eventController = new EventController(
            $this->repository
        );
    }

    /**
     * @test
     */
    public function it_returns_event_xml()
    {
        $this->assertTrue(false);
    }

    /**
     * @test
     */
    public function it_returns_bad_request_on_invalid_event_cdbid()
    {
        $this->assertTrue(false);
    }

    /**
     * @test
     */
    public function it_returns_not_found_on_unknown_event_cdbid()
    {
        $this->assertTrue(false);
    }
}
