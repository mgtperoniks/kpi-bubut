<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\MdItemMirror;

class PullMasterItems extends Command
{
    protected $signature = 'pull:master-items';
    protected $description = 'Pull active items from Master Data KPI';

    public function handle()
    {
        $items = DB::connection('master')
            ->table('md_items')
            ->where('status', 'active')
            ->get();

        $count = 0;

        foreach ($items as $item) {
            $mirror = MdItemMirror::where('code', $item->code)->first();

            if ($mirror && $mirror->source_updated_at === $item->updated_at) {
                continue; // tidak berubah
            }

            MdItemMirror::updateOrCreate(
                ['code' => $item->code],
                [
                    'name' => $item->name,
                    'cycle_time_sec' => $item->cycle_time_sec,
                    'status' => $item->status,
                    'source_updated_at' => $item->updated_at,
                    'last_sync_at' => now(),
                ]
            );

            $count++;
        }

        $this->info("Pulled {$count} items.");
    }
}
