<?php

namespace CultuurNet\UDB3\IIS\Silex\Relation;

use CultuurNet\UDB3\IISStore\Stores\RepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use ValueObjects\Exception\InvalidNativeArgumentException;
use ValueObjects\StringLiteral\StringLiteral;

class RelationController
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * EventController constructor.
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $externalId
     * @return string
     */
    public function get($externalId)
    {
        try {

            $eventCdbid = $this->repository->getEventCdbid(new StringLiteral($externalId));

            if ($eventCdbid) {
                return new Response(
                    $eventCdbid,
                    Response::HTTP_OK
                );
            } else {
                return new Response(
                    'Event with externalId ' . $externalId . ' not found.',
                    Response::HTTP_NOT_FOUND
                );
            }
        } catch (InvalidNativeArgumentException $exception) {
            return new Response(
                'Could not convert ' . $externalId . ' to valid Event.',
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
