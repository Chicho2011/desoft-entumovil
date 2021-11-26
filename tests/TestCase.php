<?php

namespace Desoft\Tests;

use Desoft\EnTuMovilServiceProvider;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

class TestCase extends TestbenchTestCase{

    protected function getPackageProviders($app)
    {
        return [
            EnTuMovilServiceProvider::class,
        ];
    }


}
