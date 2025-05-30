<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'valomizer:create-user {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if(User::where('email', $this->argument('email'))->exists()) {
            $this->error('User already exists.');
            return 1;
        }

        User::create([
            'name' => 'Admin',
            'email' => $this->argument('email'),
            'password' => bcrypt($this->argument('password')),
            'is_admin' => true,
            'is_active' => true,
        ]);

        $this->info('User created successfully.');
        return 0;
    }
}
