<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class Install extends Command
{
    protected $signature = 'statify:install';

    protected $description = 'Interactive installer — configures .env, runs migrations and creates the first admin user';

    /** Percorso assoluto del file .env */
    private string $envPath;

    public function handle(): int
    {
        $this->envPath = base_path('.env');

        $this->newLine();
        $this->line('  <fg=blue;options=bold>╔══════════════════════════════════════════╗</>');
        $this->line('  <fg=blue;options=bold>║     Statify — Interactive Installer      ║</>');
        $this->line('  <fg=blue;options=bold>╚══════════════════════════════════════════╝</>');
        $this->newLine();

        // ───────────────────────────────────────────
        // STEP 1 — Application
        // ───────────────────────────────────────────
        $this->line('  <fg=yellow;options=bold>Step 1 — Application</>');
        $this->line('  <fg=gray>──────────────────────────────────────────</>');

        $appName = $this->ask('  App name', 'Statify');
        $appEnv  = $this->choice('  Environment', ['production', 'local', 'staging'], 0);
        $appDebug = $appEnv !== 'production'
            ? $this->confirm('  Enable debug mode?', false)
            : false;
        $appUrl  = $this->ask('  App URL (e.g. https://example.com/statify)', 'http://localhost');

        $this->newLine();

        // ───────────────────────────────────────────
        // STEP 2 — Database
        // ───────────────────────────────────────────
        $this->line('  <fg=yellow;options=bold>Step 2 — Database</>');
        $this->line('  <fg=gray>──────────────────────────────────────────</>');

        $dbConnection = $this->choice('  Driver', ['mysql', 'pgsql', 'sqlite'], 0);
        $dbHost     = $this->ask('  Host', '127.0.0.1');
        $dbPort     = $this->ask('  Port', $dbConnection === 'pgsql' ? '5432' : '3306');
        $dbDatabase = $this->ask('  Database name', 'statify');
        $dbUsername = $this->ask('  Username', 'statify');
        $dbPassword = $this->secret('  Password');

        // Test connection
        $this->newLine();
        $this->line('  <fg=gray>Testing database connection...</>');

        try {
            $pdo = new \PDO(
                "{$dbConnection}:host={$dbHost};port={$dbPort};dbname={$dbDatabase}",
                $dbUsername,
                $dbPassword,
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, \PDO::ATTR_TIMEOUT => 5]
            );
            unset($pdo);
            $this->line('  <fg=green>✓ Connection successful!</>');
        } catch (\Exception $e) {
            $this->error('  ✗ Cannot connect to database: ' . $e->getMessage());
            if (! $this->confirm('  Continue anyway?', false)) {
                return self::FAILURE;
            }
            $this->warn('  ⚠ Proceeding without a verified connection.');
        }

        $this->newLine();

        // ───────────────────────────────────────────
        // STEP 3 — Mail
        // ───────────────────────────────────────────
        $this->line('  <fg=yellow;options=bold>Step 3 — Mail</>');
        $this->line('  <fg=gray>──────────────────────────────────────────</>');

        $mailMailer    = $this->choice('  Mailer driver', ['smtp', 'log', 'null'], 0);
        $mailHost      = $mailMailer === 'smtp' ? $this->ask('  SMTP host', 'smtp.example.com') : 'localhost';
        $mailPort      = $mailMailer === 'smtp' ? $this->ask('  SMTP port', '587') : '587';
        $mailEncrypt   = $mailMailer === 'smtp'
            ? $this->choice('  Encryption', ['tls', 'ssl', 'none'], 0)
            : 'tls';
        $mailScheme    = match($mailEncrypt) {
            'ssl'  => 'smtps',
            'none' => '',
            default => 'null',
        };
        $mailUsername  = $mailMailer === 'smtp' ? $this->ask('  SMTP username') : '';
        $mailPassword  = $mailMailer === 'smtp' ? $this->secret('  SMTP password') : '';
        $mailFromAddr  = $this->ask('  From address', $mailUsername ?: 'noreply@example.com');
        $mailFromName  = $this->ask('  From name', $appName);

        $this->newLine();

        // ───────────────────────────────────────────
        // STEP 4 — Telegram (optional)
        // ───────────────────────────────────────────
        $this->line('  <fg=yellow;options=bold>Step 4 — Telegram Notifications (optional)</>');
        $this->line('  <fg=gray>──────────────────────────────────────────</>');

        $telegramToken = '';
        if ($this->confirm('  Configure Telegram bot token?', false)) {
            $telegramToken = $this->ask('  Telegram Bot Token');
        }

        $this->newLine();

        // ───────────────────────────────────────────
        // SUMMARY
        // ───────────────────────────────────────────
        $this->line('  <fg=yellow;options=bold>Summary</>');
        $this->line('  <fg=gray>──────────────────────────────────────────</>');

        $this->table(
            ['Setting', 'Value'],
            [
                ['App Name',        $appName],
                ['Environment',     $appEnv],
                ['Debug',           $appDebug ? 'true' : 'false'],
                ['App URL',         $appUrl],
                ['DB Driver',       $dbConnection],
                ['DB Host',         $dbHost . ':' . $dbPort],
                ['DB Database',     $dbDatabase],
                ['DB Username',     $dbUsername],
                ['Mail Mailer',     $mailMailer],
                ['Mail Host',       $mailHost . ':' . $mailPort],
                ['Mail From',       "{$mailFromName} <{$mailFromAddr}>"],
                ['Telegram Bot',    $telegramToken ? '✓ configured' : '—'],
            ]
        );

        if (! $this->confirm('  Apply configuration and proceed?', true)) {
            $this->warn('  Aborted — no changes made.');
            return self::FAILURE;
        }

        // ───────────────────────────────────────────
        // WRITE .env
        // ───────────────────────────────────────────
        $this->newLine();
        $this->line('  <fg=gray>Writing .env file...</>');

        $replacements = [
            'APP_NAME'          => '"' . $appName . '"',
            'APP_ENV'           => $appEnv,
            'APP_DEBUG'         => $appDebug ? 'true' : 'false',
            'APP_URL'           => $appUrl,
            'ASSET_URL'         => $appUrl,

            'DB_CONNECTION'     => $dbConnection,
            'DB_HOST'           => $dbHost,
            'DB_PORT'           => $dbPort,
            'DB_DATABASE'       => $dbDatabase,
            'DB_USERNAME'       => $dbUsername,
            'DB_PASSWORD'       => $dbPassword,

            'MAIL_MAILER'       => $mailMailer,
            'MAIL_SCHEME'       => $mailScheme,
            'MAIL_HOST'         => $mailHost,
            'MAIL_PORT'         => $mailPort,
            'MAIL_ENCRYPTION'   => $mailEncrypt !== 'none' ? $mailEncrypt : 'null',
            'MAIL_USERNAME'     => $mailUsername,
            'MAIL_PASSWORD'     => $mailPassword,
            'MAIL_FROM_ADDRESS' => '"' . $mailFromAddr . '"',
            'MAIL_FROM_NAME'    => '"' . $mailFromName . '"',

            'TELEGRAM_BOT_TOKEN' => $telegramToken ? '"' . $telegramToken . '"' : '""',
        ];

        $envContent = file_get_contents($this->envPath);

        foreach ($replacements as $key => $value) {
            // Match KEY=anything (quoted or not) and replace
            $envContent = preg_replace(
                '/^' . preg_quote($key, '/') . '=.*/m',
                $key . '=' . $value,
                $envContent
            );
        }

        file_put_contents($this->envPath, $envContent);
        $this->line('  <fg=green>✓ .env updated successfully.</>');

        // ───────────────────────────────────────────
        // GENERATE KEY (if blank)
        // ───────────────────────────────────────────
        if (str_contains($envContent, 'APP_KEY=') && ! str_contains($envContent, 'APP_KEY=base64:')) {
            $this->line('  <fg=gray>Generating application key...</>');
            Artisan::call('key:generate', ['--force' => true]);
            $this->line('  <fg=green>✓ Application key generated.</>');
        }

        // Reload .env config
        $this->refreshEnv();

        // ───────────────────────────────────────────
        // MIGRATE
        // ───────────────────────────────────────────
        $this->newLine();
        $this->line('  <fg=yellow;options=bold>Running Migrations...</>');
        $this->line('  <fg=gray>──────────────────────────────────────────</>');

        try {
            Artisan::call('migrate', ['--force' => true], $this->output);
            $this->line('  <fg=green>✓ Migrations completed.</>');
        } catch (\Exception $e) {
            $this->error('  ✗ Migration failed: ' . $e->getMessage());
            return self::FAILURE;
        }

        // ───────────────────────────────────────────
        // CREATE ADMIN USER
        // ───────────────────────────────────────────
        $this->newLine();
        $this->line('  <fg=yellow;options=bold>Create First Admin User</>');
        $this->line('  <fg=gray>──────────────────────────────────────────</>');

        Artisan::call('statify:create-user', [], $this->output);

        // ───────────────────────────────────────────
        // DONE
        // ───────────────────────────────────────────
        $this->newLine();
        $this->line('  <fg=green;options=bold>╔══════════════════════════════════════════╗</>');
        $this->line('  <fg=green;options=bold>║   Statify installed successfully! 🎉     ║</>');
        $this->line('  <fg=green;options=bold>╚══════════════════════════════════════════╝</>');
        $this->newLine();
        $this->line("  Open your browser at: <options=bold>{$appUrl}</>");
        $this->newLine();

        return self::SUCCESS;
    }

    /**
     * Force Laravel to re-read the freshly written .env file
     * so that subsequent Artisan calls (migrate) use the new credentials.
     */
    private function refreshEnv(): void
    {
        $lines = file($this->envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            $parts = explode('=', $line, 2);
            if (count($parts) === 2) {
                $key = trim($parts[0]);
                $value = trim($parts[1]);
                $value = trim($value, '"\'');
                putenv("{$key}={$value}");
                $_ENV[$key]    = $value;
                $_SERVER[$key] = $value;
            }
        }

        // Re-bind DB config from new env values
        config([
            'database.connections.mysql.host'     => env('DB_HOST'),
            'database.connections.mysql.port'     => env('DB_PORT'),
            'database.connections.mysql.database' => env('DB_DATABASE'),
            'database.connections.mysql.username' => env('DB_USERNAME'),
            'database.connections.mysql.password' => env('DB_PASSWORD'),

            'database.connections.pgsql.host'     => env('DB_HOST'),
            'database.connections.pgsql.port'     => env('DB_PORT'),
            'database.connections.pgsql.database' => env('DB_DATABASE'),
            'database.connections.pgsql.username' => env('DB_USERNAME'),
            'database.connections.pgsql.password' => env('DB_PASSWORD'),
        ]);

        DB::purge();
        DB::reconnect();
    }
}
