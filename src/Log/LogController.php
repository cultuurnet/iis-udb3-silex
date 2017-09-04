<?php

namespace CultuurNet\UDB3\IIS\Silex\Log;

use Symfony\Component\HttpFoundation\Response;
use ValueObjects\Exception\InvalidNativeArgumentException;

class LogController
{
    /**
     * @var string
     */
    private $logPath;

    /**
     * @param string $logPath
     * EventController constructor.
     */
    public function __construct($logPath)
    {
        $this->logPath = $logPath;
    }

    /**
     * @param  string $date
     * @return string
     */
    public function get($date)
    {
        try {
            $logContents = $this->getContents($date);

            if ($logContents) {
                return new Response(
                    $logContents,
                    Response::HTTP_OK,
                    [
                        'Content-Type' => 'text/plain',
                    ]
                );
            } else {
                return new Response(
                    'Logfile with date ' . $date . ' not found.',
                    Response::HTTP_NOT_FOUND
                );
            }
        } catch (InvalidNativeArgumentException $exception) {
            return new Response(
                'Could not convert ' . $date . ' to valid Date.',
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    private function getContents($date)
    {
        try {
            $formattedDate = \DateTime::createFromFormat('Ymd', $date);
            if (!$formattedDate) {
                throw new InvalidNativeArgumentException('That is not a date', ['date', 'datetime']);
            }
            $fileName = $formattedDate->format('Y-m-d');
        } catch (\Exception $ex) {
            throw new InvalidNativeArgumentException('That is not a date', ['date', 'datetime']);
        }
        $path = $this->logPath . '/importer-' . $fileName . '.log';
        try {
            if (file_exists($path . '.1')) {
                $contents = file_get_contents($path . '.1');
            } else {
                $contents = file_get_contents($path);
            }
            return $contents;
        } catch (\Exception $ex) {
            return null;
        }
    }
}
