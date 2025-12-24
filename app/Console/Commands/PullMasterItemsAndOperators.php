<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\MdItemMirror;
use App\Models\MdOperatorMirror;

class PullMasterItemsAndOperators extends Command
{
    protected $signature = 'pull:master-items-operators';
    protected $description = 'Pull master items and operators from Master Data';

    public function handle(): int
    {
        // ITEMS
        $items = DB::connection('master')->table('md_items')->get();

        foreach ($items as $item) {
            MdItemMirror::updateOrCreate(
                ['code' => $item->code],
                [
                    'name' => $item->name,
                    'department_code' => $item->department_code,
                    'cycle_time_sec' => $item->cycle_time_sec,
                    'status' => $item->status,
                ]
            );
        }

        // OPERATORS
        $operators = DB::connection('master')->table('md_operators')->get();

        foreach ($operators as $op) {
            MdOperatorMirror::updateOrCreate(
                ['code' => $op->code],
                [
                    'name' => $op->name,
                    'department_code' => $op->department_code,
                    'status' => $op->status,
                ]
            );
        }

        $this->info("Pulled {$items->count()} items & {$operators->count()} operators.");
        return Command::SUCCESS;
    }
}
