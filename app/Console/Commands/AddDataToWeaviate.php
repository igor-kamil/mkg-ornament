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
        $items = Item::whereNotNull('asset_id')->get();
        $totalItems = $items->count();

        $progressBar = $this->output->createProgressBar($totalItems);
        $progressBar->start();

        foreach ($items as $item) {
            $imagePath = storage_path("app/images/{$item->id}.jpg");

            if (file_exists($imagePath)) {
                $image = file_get_contents($imagePath);
                $imageBase64 = base64_encode($image);
                $weaviate = new Weaviate(config('services.weaviate.url'), config('services.weaviate.token'));
                $object = $weaviate->dataObject()->create([
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
                ]);
            }
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->line('');
        $this->info('Data for ' . $totalItems. ' objects has been added to ' . $this->className . ' class in Weaviate.');
    }
}
