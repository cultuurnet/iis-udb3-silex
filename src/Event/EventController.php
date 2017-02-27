<?php

namespace CultuurNet\UDB3\IIS\Silex\Event;

use CultuurNet\UDB3\IISStore\Stores\RepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use ValueObjects\Exception\InvalidNativeArgumentException;
use ValueObjects\Identity\UUID;

class EventController
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
     * @param string $eventCdbid
     * @return string
     */
    public function get($eventCdbid)
    {
        try {
            $eventCdbidAsUuid = new UUID($eventCdbid);
            $eventXml = $this->repository->getEventXml($eventCdbidAsUuid);

            if ($eventXml) {
                return new Response(
                    $eventXml,
                    Response::HTTP_OK,
                    [
                        'Content-Type' => 'application/xml',
                    ]
                );
            } else {
                return new Response(
                    'Event with id ' . $eventCdbidAsUuid->toNative() . ' not found.',
                    Response::HTTP_NOT_FOUND
                );
            }
        } catch (InvalidNativeArgumentException $exception) {
            return new Response(
                'Could not convert ' . $eventCdbid . ' to valid UUID.',
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
