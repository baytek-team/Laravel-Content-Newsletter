<?php
namespace Baytek\Laravel\Content\Types\Newsletter\Commands;

use Baytek\Laravel\Content\Models\Content;
use Baytek\Laravel\Content\Commands\Installer;
use Baytek\Laravel\Content\Types\Newsletter\Seeders\NewsletterSeeder;
use Baytek\Laravel\Content\Types\Newsletter\Seeders\FakeDataSeeder;
use Baytek\Laravel\Content\Types\Newsletter\Newsletter;
use Baytek\Laravel\Content\Types\Newsletter\NewsletterContentServiceProvider;
use Spatie\Permission\Models\Permission;

use Artisan;
use DB;

class NewsletterInstaller extends Installer
{
    public $name = 'Newsletter';
    protected $protected = ['Newsletter'];
    protected $provider = NewsletterContentServiceProvider::class;
    protected $model = Newsletter::class;
    protected $seeder = NewsletterSeeder::class;
    protected $fakeSeeder = FakeDataSeeder::class;
    protected $migrationPath = __DIR__.'/../resources/Database/Migrations';

    public function shouldPublish()
    {
        return true;
    }

    public function shouldMigrate()
    {
        $pluginTables = [
            env('DB_PREFIX', '').'contents',
            env('DB_PREFIX', '').'content_meta',
            env('DB_PREFIX', '').'content_histories',
            env('DB_PREFIX', '').'content_relations',
        ];

        return collect(array_map('reset', DB::select('SHOW TABLES')))
            ->intersect($pluginTables)
            ->isEmpty();
    }

    public function shouldSeed()
    {
        $relevantRecords = [
            'newsletter',
        ];

        return Content::whereIn('contents.key', $relevantRecords)->count() === 0;
    }

    public function shouldProtect()
    {
        foreach ($this->protected as $model) {
            foreach(['view', 'create', 'update', 'delete'] as $permission) {

                // If the permission exists in any form do not reseed.
                if(Permission::where('name', title_case($permission.' '.$model))->exists()) {
                    return false;
                }
            }
        }

        return true;
    }
}
