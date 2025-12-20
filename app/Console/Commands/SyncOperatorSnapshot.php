<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncOperatorSnapshot extends Command
{
    protected $signature = 'master:sync-operator';

    public function handle()
    {
        // contoh dummy, nanti ganti dari CSV / DB master
        OperatorSnapshot::updateOrCreate(
            ['operator_code' => 'afin'],
            [
                'operator_name' => 'Afin',
                'status' => 'ACTIVE',
                'synced_at' => now()
            ]
        );

        $this->info('Operator snapshot synced');
    }
}
