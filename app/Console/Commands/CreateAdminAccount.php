<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Hash;
use Illuminate\Console\Command;
use App\Models\User;

class CreateAdminAccount extends Command
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
    protected $description = 'Make Admin Account';

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
     * @return int
     */
    public function handle()
    {
        $fullname = $this->ask('Type Fullname (required)');
        $username = $this->ask('Type Username (required)');
        $password = $this->ask('Type Password (required, min 6 character)');

        if ($fullname && $username && $password && strlen($password) >= 6) {
            $usernameExist = User::where('username', $username)->first();
            if ($usernameExist) {
                $this->info("Username is already exist. Try another username");
            } else {
                User::create([
                    "username" => $username,
                    "password" => Hash::make($password),
                    "fullname" => $fullname,
                ]);
                $this->info("Success create account");
            }
        } else {
            $this->info("Fullname, Username, and Password are required");
        }
    }
}
