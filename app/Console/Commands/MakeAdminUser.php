<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeAdminUser extends Command
{
    /**
     * @var string
     */
    protected $signature = 'sard:make-admin {email : The email of an existing account}';

    /**
     * @var string
     */
    protected $description = 'Give an existing account administrator access';

    /**
     * Promote the matching account, if it exists.
     */
    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('No account uses that email.');

            return self::FAILURE;
        }

        $user->forceFill(['is_admin' => true])->save();

        $this->info('Administrator access granted.');

        return self::SUCCESS;
    }
}
