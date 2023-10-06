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
    protected $signature = 'import:assets {file} {--tif}';

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
        $rows = SimpleExcelReader::create($filePath)->useDelimiter(';')->getRows();

        $rows->each(function (array $row) {
            try {
                if (Str::endsWith(Arr::get($row, 'Asset-Name'), '.jpg') && !Str::contains(Arr::get($row, 'Asset-Name'), '+')) {
                    $id = Str::remove('.jpg', Arr::get($row, 'Asset-Name'));
                    Item::where('id', $id)->update(['asset_id' => Arr::get($row, 'Media Delivery Cloud Asset ID')]);
                }
                if ($this->option('tif') && Str::endsWith(Arr::get($row, 'Asset-Name'), '.tif') && !Str::contains(Arr::get($row, 'Asset-Name'), '+')) {
                    $id = Str::remove('.tif', Arr::get($row, 'Asset-Name'));
                    $item = Item::whereNull('asset_id')->where('id', $id)->first();
                    if ($item) {
                        Item::where('id', $id)->update(['asset_id' => Arr::get($row, 'Media Delivery Cloud Asset ID')]);
                    }

                }
            } catch (QueryException $e) {
                // Log the exception and continue with the next row
                Log::error('Error processing item: ' . Arr::get($row, 'Inventarnummer'));
                Log::error('Exception: ' . $e->getMessage());
            }
        });

        $this->info('Assets imported successfully.');
    }
}
