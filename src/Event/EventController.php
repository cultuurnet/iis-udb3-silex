<?php

namespace CultuurNet\UDB3\IIS\Silex\Event;

use Symfony\Component\HttpFoundation\Response;

class EventController
{
    /**
     * @param string $eventCdbid
     * @return string
     */
    public function get($eventCdbid)
    {
        return Response::create('Event ' . $eventCdbid);
    }
}
