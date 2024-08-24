<?php

namespace Webkul\Marketplace\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Marketplace\Database\Seeders\DatabaseSeeder;

class InstallMarketplace extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marketplace:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Marketplace Module';

    /**
     * Install and configure Marketplace.
     */
    public function handle()
    {
        $this->warn('Step 1: Migrating all tables into database (will take a while)...');
        $this->call('migrate');

        $this->warn('Step 2: Seeding data into database tables...');
        $this->info(app(DatabaseSeeder::class)->run());

        $this->warn('Step 3: Clearing cached bootstrap files...');
        $this->call('optimize:clear');
    }
}
