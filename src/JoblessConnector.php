<?php

namespace Nollaversio\SQSJobless;

use Aws\Sqs\SqsClient;
use Illuminate\Support\Arr;
use Illuminate\Queue\Connectors\SqsConnector;
use Illuminate\Queue\Connectors\ConnectorInterface;

class JoblessConnector extends SqsConnector implements ConnectorInterface
{
    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        $config = $this->getDefaultConfiguration($config);

        if ($config['key'] && $config['secret']) {
            $config['credentials'] = Arr::only($config, ['key', 'secret']);
        }

        return new JoblessQueue(
            new SqsClient($config), $config['queue'], Arr::get($config, 'prefix', '')
        );
    }


}

