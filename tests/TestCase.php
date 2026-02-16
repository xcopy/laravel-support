<?php

namespace Jenishev\Laravel\Support\Tests;

use Jenishev\Laravel\Support\SupportServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            SupportServiceProvider::class,
        ];
    }
}
