<?php

namespace App\Console\Commands;

use App\Admin\Models\Admin;
use App\Models\User;
use Illuminate\Console\Command;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!User::query()->where('email', 'admin@admin.com')->first()) {
            /** @var User $user */
            $user = Admin::query()->create([
                'email' => 'admin@admin.com',
                'phone' => '48093216263',
                'password' => 'qazwsx12',
                'phone_verified_at' => '2021-04-19 11:32:56',
            ]);

            $user->syncRoles('admin');
            $user->syncPermissions(['admin', 'superadmin']);
        }

    }
}
