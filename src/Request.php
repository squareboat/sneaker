<?php

namespace SquareBoat\Sneaker;

use Illuminate\Support\Arr;
use Illuminate\Http\Request as IlluminateRequest;

class Request
{
    /**
     * The illuminate request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Create a new illuminate request instance.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function __construct(IlluminateRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Get the request formatted as meta data.
     *
     * @return array
     */
    public function getMetaData()
    {
        $data = [];

        $data['URL'] = $this->request->fullUrl();

        $data['Method'] = $this->request->getMethod();

        $data['IP-Address'] = $this->request->getClientIp();

        if ($headers = $this->request->headers->all()) {
            $data['Host'] = Arr::get($headers, 'host.0');

            $data['Connection'] = Arr::get($headers, 'connection.0');

            $data['Upgrade-Insecure-Requests'] = Arr::get($headers, 'upgrade-insecure-requests.0');

            $data['User-Agent'] = Arr::get($headers, 'user-agent.0');

            $data['Accept'] = Arr::get($headers, 'accept.0');

            $data['Referer'] = Arr::get($headers, 'referer.0');

            $data['Accept-Encoding'] = Arr::get($headers, 'accept-encoding.0');

            $data['Accept-Language'] = Arr::get($headers, 'accept-language.0');
        }

        return $data;
    }
}