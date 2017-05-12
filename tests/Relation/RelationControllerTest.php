<?php

namespace CultuurNet\UDB3\IIS\Silex\Relation;

use CultuurNet\UDB3\IISStore\Stores\RepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use ValueObjects\Identity\UUID;

class RelationControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $repository;

    /**
     * @var RelationController
     */
    private $relationController;

    protected function setUp()
    {
        $this->repository = $this->createMock(RepositoryInterface::class);

        $this->relationController = new RelationController(
            $this->repository
        );
    }

    /**
     * @test
     */
    public function it_returns_event_cdbid()
    {
        $externalId = 'ftp_test:1234';
        $uuid = new UUID();

        $this->repository->expects($this->once())
            ->method('getEventCdbid')
            ->with($externalId)
            ->willReturn($uuid->toNative());

        $expectedResponse = new Response(
            $uuid->toNative()
        );

        $response = $this->relationController->get($externalId);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @test
     */
    public function it_returns_not_found_on_unknown_external_id()
    {
        $externalId = 'ftp_test:4321';

        $this->repository->expects($this->once())
            ->method('getEventCdbid')
            ->with($externalId)
            ->willReturn(null);

        $expectedResponse = new Response(
            'Event with externalId ' . $externalId . ' not found.',
            Response::HTTP_NOT_FOUND
        );

        $response = $this->relationController->get($externalId);

        $this->assertEquals($expectedResponse, $response);
    }
}
