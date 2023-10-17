<?php

namespace App\Console\Commands;

use App\Models\Item;
use Spatie\Image\Image;
use Illuminate\Console\Command;
use Spatie\MediaLibrary\Support\ImageFactory;
use Spatie\TemporaryDirectory\TemporaryDirectory;
use Spatie\MediaLibrary\ResponsiveImages\TinyPlaceholderGenerator\TinyPlaceholderGenerator;

class GenerateTinyPlaceholders extends Command
{
    protected $signature = 'generate:tiny-placeholders';
    protected $description = 'Generate tiny placeholders for all items in the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $items = Item::whereNotNull('asset_id')->get();

        $bar = $this->output->createProgressBar($items->count());
        $bar->start();
        $temporaryDirectory = (new TemporaryDirectory())->create();
        
        
        foreach ($items as $item) {
            $imagePath = storage_path("app/images/{$item->id}.jpg");

            if (file_exists($imagePath)) {
                try {


                    $tempDestination = $temporaryDirectory->path('tiny.jpg');
                    $sourceImage = Image::load($imagePath);
                    $originalImageWidth = $sourceImage->getWidth();
                    $originalImageHeight = $sourceImage->getHeight();

                    $sourceImage->width(32)->blur(5)->save($tempDestination);

                    $tinyImageDataBase64 = base64_encode(file_get_contents($tempDestination));

                    $tinyImageBase64 = 'data:image/jpeg;base64,' . $tinyImageDataBase64;

                    $svg = view('media-library::placeholderSvg', compact(
                        'originalImageWidth',
                        'originalImageHeight',
                        'tinyImageBase64'
                    ));

                    $base64Svg = 'data:image/svg+xml;base64,' . base64_encode($svg);
                    // Store the tiny placeholder in your database
                    $item->update(['tiny_placeholder' => $base64Svg]);
                } catch (\Exception $e) {                    
                    // Log the ID with a wrong image
                    $this->error("Failed to generate a tiny placeholder for item with ID: {$item->id}");
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->info('Tiny placeholders generated and stored.');
    }
}
