<?php

namespace App\Console\Commands;

use App\Models\Item;
use Weaviate\Weaviate;
use Illuminate\Console\Command;

class AddDataToWeaviate extends Command
{
    protected $signature = 'weaviate:add-data';
    protected $description = 'Add data to Weaviate class';

    protected $className = 'Ornament'; // define your own schema class name here

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $weaviate = new Weaviate(config('services.weaviate.url'), config('services.weaviate.token'));

        $weaviate->batch()->delete($this->className, [
            "operator" => "NotEqual",
            "path" => ["id"],
            "valueString" => "x"
        ]);

        $itemsQuery = Item::whereNotNull('asset_id');
        $totalItems = $itemsQuery->count();

        $progressBar = $this->output->createProgressBar($totalItems);
        $progressBar->start();

        $chunkSize = 30;
        $itemsQuery->chunk($chunkSize, function ($items) use ($weaviate, $progressBar) {
            $items_bulk = [];
            foreach ($items as $item) {
                $imagePath = storage_path("app/images/{$item->id}.jpg");

                if (file_exists($imagePath)) {
                    try {
                        $image = file_get_contents($imagePath);
                        $imageBase64 = base64_encode($image);


                        // $object = $weaviate->dataObject()->create([
                        $items_bulk[] = [
                            'class' => $this->className,
                            'properties' => [
                                'identifier' => $item->id,
                                'object' => $item->object,
                                'title' => $item->title,
                                'material' => $item->material,
                                'technique' => $item->technique,
                                'iconography' => $item->iconography,
                                'style' => $item->style,
                                'year' => $item->year,
                                'collection' => $item->collection,
                                'image' => $imageBase64,
                            ]
                        ];
                    } catch (\Exception $e) {
                        $this->error('An error occurred while adding data to Weaviate: ' . $e->getMessage());
                        continue;
                    }
                }
                $progressBar->advance();
            }
            $weaviate->batch()->create($items_bulk);
        });

        $progressBar->finish();
        $this->line('');
        $this->info('Data for ' . $totalItems . ' objects has been added to ' . $this->className . ' class in Weaviate.');
    }
}
