<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateUser extends Command
{
    protected $signature = 'statify:create-user
                            {--name= : Full name of the user}
                            {--email= : Email address}
                            {--password= : Password (min 8 chars)}';

    protected $description = 'Create a new Statify user interactively or via options';

    public function handle(): int
    {
        $this->newLine();
        $this->line('  <fg=blue;options=bold>┌─────────────────────────────────┐</>');
        $this->line('  <fg=blue;options=bold>│   Statify — Create New User     │</>');
        $this->line('  <fg=blue;options=bold>└─────────────────────────────────┘</>');
        $this->newLine();

        // --- Name ---
        $name = $this->option('name') ?? $this->ask('  Full name');

        $nameValidator = Validator::make(['name' => $name], [
            'name' => ['required', 'string', 'min:2', 'max:255'],
        ]);
        if ($nameValidator->fails()) {
            $this->error('  ✗ ' . $nameValidator->errors()->first('name'));
            return self::FAILURE;
        }

        // --- Email ---
        $email = $this->option('email') ?? $this->ask('  Email address');

        $emailValidator = Validator::make(['email' => $email], [
            'email' => ['required', 'email', 'unique:users,email'],
        ]);
        if ($emailValidator->fails()) {
            $this->error('  ✗ ' . $emailValidator->errors()->first('email'));
            return self::FAILURE;
        }

        // --- Password ---
        if ($this->option('password')) {
            $password = $this->option('password');
            $confirm  = $password;
        } else {
            $password = $this->secret('  Password (min 8 chars)');
            $confirm  = $this->secret('  Confirm password');
        }

        $passwordValidator = Validator::make(
            ['password' => $password, 'confirm' => $confirm],
            [
                'password' => ['required', 'string', 'min:8'],
                'confirm'  => ['required', 'same:password'],
            ],
            [
                'confirm.same' => 'Passwords do not match.',
            ]
        );
        if ($passwordValidator->fails()) {
            $this->error('  ✗ ' . $passwordValidator->errors()->first());
            return self::FAILURE;
        }

        // --- Confirm creation ---
        $this->newLine();
        $this->table(
            ['Field', 'Value'],
            [
                ['Name',  $name],
                ['Email', $email],
            ]
        );

        if (! $this->confirm('  Create this user?', true)) {
            $this->warn('  Aborted.');
            return self::FAILURE;
        }

        // --- Create ---
        $user = User::create([
            'name'              => $name,
            'email'             => $email,
            'password'          => Hash::make($password),
            'email_verified_at' => now(),
            'type'              => 'administrator',
        ]);

        $this->newLine();
        $this->line("  <fg=green;options=bold>✓ User created successfully!</>");
        $this->line("  <fg=gray>ID    :</> <options=bold>{$user->id}</>");
        $this->line("  <fg=gray>Name  :</> {$user->name}");
        $this->line("  <fg=gray>Email :</> {$user->email}");
        $this->newLine();

        return self::SUCCESS;
    }
}
