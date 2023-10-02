<?php

namespace App\Console\Commands;

use Airtable;
use App\Models\Item;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Spatie\SimpleExcel\SimpleExcelReader;

class ImportUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:urls {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import urls from a CSV file';

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
        $filePath = $this->argument('file');

        // Read the CSV file using spatie/simple-excel
        $rows = SimpleExcelReader::create($filePath)->getRows();

        $rows->each(function (array $row) {
            $item = Item::find(Arr::get($row, 'inventoryId'));
            if ($item) {
                $item->web_url = 'https://sammlungonline.mkg-hamburg.de/de/object/'.
                    Str::of(Arr::get($row, 'title'))->slug('-')->limit(100, '').'/'.
                    Arr::get($row, 'inventoryId').'/'.Arr::get($row, 'id');
                $item->save();
            }
        });

        $this->info('Web urls imported successfully.');
    }
}
