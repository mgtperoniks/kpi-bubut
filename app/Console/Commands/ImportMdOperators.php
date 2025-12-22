<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MdOperator;
use Illuminate\Support\Facades\File;

class ImportMdOperators extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'md:import-operators';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import master operator dari file CSV ke tabel md_operators';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $path = storage_path('app/import/md_operators.csv');

        if (!File::exists($path)) {
            $this->error('File tidak ditemukan: ' . $path);
            return Command::FAILURE;
        }

        $rows = array_map('str_getcsv', file($path));

        if (count($rows) <= 1) {
            $this->error('File CSV kosong atau hanya berisi header');
            return Command::FAILURE;
        }

        $imported = 0;

        foreach ($rows as $index => $row) {
            // Skip header
            if ($index === 0) {
                continue;
            }

            if (count($row) < 2) {
                continue; // baris tidak valid
            }

            $code   = trim($row[0]);
            $name   = trim($row[1]);
            $active = isset($row[2]) ? (int) $row[2] : 1;

            if ($code === '' || $name === '') {
                continue;
            }

            MdOperator::updateOrCreate(
                ['code' => $code],
                [
                    'name'   => $name,
                    'active' => $active ? 1 : 0,
                ]
            );

            $imported++;
        }

        $this->info("Import master operator selesai. Total diproses: {$imported}");

        return Command::SUCCESS;
    }
}
