<?php

namespace Nollaversio\SQSJobless;

use Aws\Sqs\SqsClient;
use Illuminate\Queue\Jobs\SqsJob;
use Illuminate\Queue\SqsQueue;
use Illuminate\Contracts\Queue\Queue as QueueContract;

class JoblessQueue extends SqsQueue {

    /**
     * Pop the next job off of the queue.
     *
     * @param  string  $queue
     * @return \Illuminate\Contracts\Queue\Job|null
     */
    public function pop($queue = null)
    {
        $queue = $this->getQueue($queue);

        $response = $this->sqs->receiveMessage([
            'QueueUrl' => $queue,
            'AttributeNames' => ['ApproximateReceiveCount'],
            'MaxNumberOfMessages' => 10
        ]);

        if (count($response['Messages']) > 0) {

            foreach ($response['Messages'] as $key => $message) {

                return new JoblessJob($this->container, $this->sqs, $message, 'sqs-jobless', $queue);

            }

        }
    }	

}