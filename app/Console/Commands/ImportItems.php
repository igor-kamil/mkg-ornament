<?php

namespace App\Console\Commands;

use Airtable;
use App\Models\Item;
use Illuminate\Support\Facades\File;
use App\Models\Exhibition;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Spatie\SimpleExcel\SimpleExcelReader;

class ImportItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:items {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import items from a CSV file or folder containing CSV files';

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
        if (File::isDirectory($filePath)) {
            $csvFiles = glob($filePath . '/*.csv');
            foreach ($csvFiles as $csvFile) {
                $this->importFile($csvFile);
            }
        } else {
            $this->importFile($filePath);
        }
        $this->info('Items imported successfully.');
    }

    private function importFile($filePath)
    {
        $filename = pathinfo($filePath, PATHINFO_FILENAME);
        $this->info("Importing data from: $filename");
        $rows = SimpleExcelReader::create($filePath)->useDelimiter(';')->getRows();

        $rows->each(function (array $row) {
            try {
                Item::upsert([
                    'id' => Arr::get($row, 'Inventarnummer'),
                    'object' => Arr::get($row, 'Objektbezeichnung'),
                    'title' => Arr::get($row, 'Titel'),
                    'author' => Arr::get($row, 'Beteiligte Person(en) / Institution(en)'),
                    // 'description' => Arr::g$row, et(''),
                    'material' => Arr::get($row, 'Material'),
                    'technique' => Arr::get($row, 'Technik'),
                    'iconography' => Str::limit(Arr::get($row, 'Ikonographie'), 255),
                    'style' => Arr::get($row, 'Stil'),
                    // 'image_src' => Arr::g$row, et(''),
                    // 'web_url' => Arr::g$row, et(''),
                    'collection' => Arr::get($row, 'Sammlung'),
                    'year_from' => Arr::get($row, 'Datierung von'),
                    'year_to' => Arr::get($row, 'Datierung bis'),
                    'acquisition_date' => Arr::get($row, 'Zugangsdatum'),
                ], ['id']);
            } catch (QueryException $e) {
                // Log the exception and continue with the next row
                Log::error('Error processing item: ' . Arr::get($row, 'Inventarnummer'));
                Log::error('Exception: ' . $e->getMessage());
            }
            // in the first pass $rowProperties will contain
            // ['email' => 'john@example.com', 'first_name' => 'john']
        });
    }
}
