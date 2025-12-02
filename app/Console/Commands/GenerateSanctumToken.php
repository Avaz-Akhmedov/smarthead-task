<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateSanctumToken extends Command
{

    protected $signature = 'generate:token';


    protected $description = 'Generate sanctum token';


    public function handle(): int
    {
        $userId = $this->ask('Enter user id');

        $user = User::query()->find($userId);

        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return self::FAILURE;
        }

        $this->info("User found:");
        $this->line("Name:  {$user->name}");
        $this->line("Email: {$user->email}");
        $this->newLine();

        if (!$this->confirm("Is this the correct user?")) {
            $this->warn('Cancelled.');
            return self::SUCCESS;
        }

        $token = $user->createToken('auth_token');

        $this->info('New token created:');
        $this->line($token->plainTextToken);
        $this->newLine();

        return self::SUCCESS;
    }
}
