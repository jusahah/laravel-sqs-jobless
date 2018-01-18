# laravel-sqs-jobless

Allows receiving custom messages from Amazon SQS. 

Note that message does not need to be in JSON either. Handler (by default JoblessHandler) will be called with raw message string. You can then do whatever you want with the string.

### Why?

Because Laravel by default only allows receiving *job messages* from SQS. Those job messages have very strict form. Any incoming SQS message not following the form will be released back to the queue.

This component allows arbitrary SQS messages to be received and handled by the Laravel.

** Works on Laravel 5.3. **

Does not work on Laravel 5.2 or earlier.

Update 18.1.18: Kindly developed further by geraldosm, should work on Laravel 5.5.

### Install

#### Step 1:

```
composer require nollaversio/laravel-sqs-jobless
```
**Note!** If you get installation error because of minimum-stability issue, you need to add key-pair *"minimum-stability": "dev"* to your composer.json file.

#### Step 2:

Add Service Provider

```php
'providers' => [
    // ...
    'Nollaversio\SQSJobless\JoblessSQSServiceProvider',
];
```

#### Step 3:

```
php artisan vendor:publish
```

### Usage

Usage needs four steps:

1. Make sure service provider is added.
2. Create new record to *config/queue.php*
3. Create *App/Jobs/JoblessHandler* class
4. Change queue driver to *sqs-jobless*
5. Start the queue.


### 1.

```php
'providers' => [
    // ...
    'Nollaversio\SQSJobless\JoblessSQSServiceProvider',
];

```

### 2.

```php
// app.config.queue.php

'sqs-jobless' => [
    'driver' => 'sqs-jobless',
    'key' => '1122334455667788XX',
    'secret' => 'xxxxxxxxxxxxxxxxxxxxxxxxxx',
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
       \Log::info($this->passedInData);
       // Check laravel.log, it should now contain msg string.
    }
}

```

### 4.

```php

// .env

QUEUE_DRIVER=sqs-jobless

```

### 5.

On command line:

```
php artisan queue:work
```

### Custom msg handler

You can easily define your own handler class for messages. You can do this on *config.sqs-jobless.php*. 

Note that only one handler can be defined at a time.

