<?php

namespace CleaniqueCoders\SocialiteRecall\Tests;

use CleaniqueCoders\SocialiteRecall\SocialiteRecallServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\TestCase as Orchestra;
use Workbench\App\Models\User;

#[WithMigration]
class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            function (string $modelName) {
                return 'Workbench\\Database\\Factories\\'.class_basename($modelName).'Factory';
            }
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            SocialiteRecallServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('socialite-recall.providers', ['github']);
        config()->set('socialite-recall.model', User::class);
        config()->set('auth.providers.users.model', User::class);
    }
}
