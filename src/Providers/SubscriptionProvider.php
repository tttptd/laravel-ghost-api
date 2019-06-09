<?php

namespace Tttptd\GhostAPI\Providers;

use Illuminate\Support\Collection;

class SubscriptionProvider extends BaseProvider
{

    /**
     * @var string
     */
    protected $postUrl = '/subscribe/';

    public function post($data)
    {
        $this->client->requestAdmin(
            'subscribers',
            $data,
            'POST'
        );
    }

}
