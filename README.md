# laravel-sqs-jobless

Allows receiving custom messages from Amazon SQS.

### Install

```
composer require nollaversio/laravel-sqs-jobless
```

and

```
php artisan vendor:publish
```

### Usage

Usage needs four steps:

1. Register service provider
2. Create new record to *config/queue.php*
3. Create *App/Jobs/JoblessHandler* class
4. Change queue driver to *sqs-jobless*


### 1.

```php
'providers' => [
    '...',
    'Nollaversio\SQSJobless\JoblessSQSServiceProvider',
];

```

### 2.

```php
// app.config.queue.php

'sqs-jobless' => [
    'driver' => 'sqs-jobless',
    'key' => '1122334455667788XX',
    'secret' => 'q150V7q+63+nyGmrPWb4Sz0AzssVPmGMtsB0xaPJ',
    'prefix' => 'https://sqs.eu-central-1.amazonaws.com/11223344556677',
    'queue' => 'msgs',
    'region' => 'eu-central-1',
], 
```

### 3.

```php

// App\Jobs\JoblessHandler.php

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class JoblessHandler implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $passedInData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        // $data is STRING containing the msg content from SQS
        $this->passedInData = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       dd($this->passedInData);
    }
}

```

### 4.

```php

// .env

QUEUE_DRIVER=sqs-jobless

```

### Custom job handler

You can easily define your own handler class for messages. You can do this on *config.sqs-jobless.php*. 

Note that only one handler can be defined at a time.
