<?php

namespace CultuurNet\UDB3\IIS\Silex\Event;

use CultuurNet\UDB3\IISStore\Stores\RepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use ValueObjects\Identity\UUID;

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
        $uuid = new UUID();
        $eventXml = '<xml>test</xml>';

        $this->repository->expects($this->once())
            ->method('getEventXml')
            ->with($uuid->toNative())
            ->willReturn($eventXml);

        $expectedResponse = new Response(
            $eventXml,
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/xml',
            ]
        );

        $response = $this->eventController->get($uuid->toNative());

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @test
     */
    public function it_returns_bad_request_on_invalid_event_cdbid()
    {
        $eventCdbid = 'd4508db3-46d5-415a-b288-a64327eda3e@';

        $expectedResponse = new Response(
            'Could not convert ' . $eventCdbid . ' to valid UUID.',
            Response::HTTP_BAD_REQUEST
        );

        $response = $this->eventController->get($eventCdbid);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @test
     */
    public function it_returns_not_found_on_unknown_event_cdbid()
    {
        $uuid = new UUID();

        $this->repository->expects($this->once())
            ->method('getEventXml')
            ->with($uuid->toNative())
            ->willReturn(null);

        $expectedResponse = new Response(
            'Event with id ' . $uuid->toNative() . ' not found.',
            Response::HTTP_NOT_FOUND
        );

        $response = $this->eventController->get($uuid->toNative());

        $this->assertEquals($expectedResponse, $response);
    }
}
