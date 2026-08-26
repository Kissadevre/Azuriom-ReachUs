<?php

namespace Azuriom\Plugin\ReachUs\Tests;

use Azuriom\Http\Controllers\InstallController;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    public function createApplication(): Application
    {
        $configCache = __DIR__.'/cache/reachus-config.php';

        if (is_file($configCache)) {
            throw new RuntimeException('Reach Us tests refuse to load a cached application configuration.');
        }

        $this->setEnvironmentVariables([
            'APP_ENV' => 'testing',
            'APP_KEY' => InstallController::TEMP_KEY,
            'APP_CONFIG_CACHE' => $configCache,
            'DB_CONNECTION' => 'sqlite',
            'DB_PATH' => ':memory:',
            'DB_URL' => '(null)',
            'LOG_CHANNEL' => 'null',
        ]);

        $app = require dirname(__DIR__, 3).'/bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();

        if (config('database.default') !== 'sqlite'
            || config('database.connections.sqlite.database') !== ':memory:') {
            throw new RuntimeException('Reach Us tests refuse to run outside SQLite memory.');
        }

        config([
            'app.key' => 'base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=',
            'app.previous_keys' => [],
        ]);
        DB::purge('sqlite');

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();

        if (DB::connection('sqlite')->getDatabaseName() !== ':memory:') {
            throw new RuntimeException('Reach Us tests refuse to run outside SQLite memory.');
        }

        foreach ([
            '2014_10_12_000000_create_users_table.php',
            '2019_08_15_000000_create_roles_table.php',
            '2019_08_12_000000_create_posts_table.php',
            '2019_08_13_000000_create_pages_table.php',
            '2019_08_22_000000_create_settings_table.php',
            '2019_08_30_000000_create_permissions_table.php',
            '2020_05_01_000000_create_notifications_table.php',
        ] as $migration) {
            (require dirname(__DIR__, 3).'/database/migrations/'.$migration)->up();
        }

        (require dirname(__DIR__).'/database/migrations/2026_08_26_000000_create_reachus_messages_table.php')->up();
    }

    private function setEnvironmentVariables(array $variables): void
    {
        foreach ($variables as $key => $value) {
            putenv($key.'='.$value);
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}
