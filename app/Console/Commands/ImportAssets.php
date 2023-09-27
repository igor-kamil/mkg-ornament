<?php

namespace App\Console\Commands;

use Airtable;
use App\Models\Item;
use App\Models\ItemAsset;
use App\Models\Exhibition;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Spatie\SimpleExcel\SimpleExcelReader;

class ImportAssets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:assets {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import assets from a CSV file';

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
            try {
                ItemAsset::upsert([
                    'id' => Arr::get($row, 'lfd. Nr.'),
                    'name' => Arr::get($row, 'Asset-Name'),
                    'mdc_id' => Arr::get($row, 'Media Delivery Cloud Asset ID'),
                    'item_id' => Arr::get($row, 'dc_Inventarnummer'),
                ], ['id']);
            } catch (QueryException $e) {
                // Log the exception and continue with the next row
                Log::error('Error processing item: ' . Arr::get($row, 'Inventarnummer'));
                Log::error('Exception: ' . $e->getMessage());
            }
            // in the first pass $rowProperties will contain
            // ['email' => 'john@example.com', 'first_name' => 'john']
        });

        $this->info('Assets imported successfully.');
    }
}
