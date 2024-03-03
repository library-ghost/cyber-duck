<?php

namespace Tests\Traits;

use Illuminate\Foundation\Testing\DatabaseMigrations;

trait SetupDatabaseMigrations
{
    use DatabaseMigrations {
        runDatabaseMigrations as baseRunDatabaseMigrations;
    }

    public function runDatabaseMigrations()
    {
        $this->baseRunDatabaseMigrations();
        $this->artisan('db:seed');
    }
}
