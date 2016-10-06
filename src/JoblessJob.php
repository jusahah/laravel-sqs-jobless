<?php

namespace Nollaversio\SQSJobless;

use Aws\Sqs\SqsClient;
use Illuminate\Queue\Jobs\SqsJob;
use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\Job as JobContract;

class JoblessJob extends SqsJob implements JobContract
{
	// This is the key method to replace.
	// We need to inject some stuff into the response received
	// from Amazon SQS so that it looks like valid Job object
    public function getRawBody()
    {

        $realBody = $this->job['Body'];

        $class = config('sqs-jobless.handler');

        $transformedBody = json_encode([

            "job" => "Illuminate\Queue\CallQueuedHandler@call",
            "data" => [
                "commandName" => $class,
                // We pass real body in after decoding it
                "command" => serialize(new $class($realBody))

            ]

        ]);

        return $transformedBody;

    }



}