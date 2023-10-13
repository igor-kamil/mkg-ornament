<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Helper\ProgressBar;

class DownloadImages extends Command
{
    protected $signature = 'download:images';
    protected $description = 'Download images for all items';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if (!Storage::exists('images')) {
            Storage::makeDirectory('images');
        }
        
        $items = Item::whereNotNull('asset_id')->get();
        $totalItems = $items->count();

        $progressBar = $this->output->createProgressBar($totalItems);
        $progressBar->start();

        foreach ($items as $item) {
            // $imageUrl = $item->getImageUrl();
            $imageUrl = 'https://mdo.mkg-hamburg.de/MDO/mediadelivery/rendition/' . $item->asset_id . '/-FJPG-B244';

            if (!$this->isImageDownloaded($imageUrl)) {
                if ($this->downloadImage($imageUrl, $item->id)) {
                    // $this->info("Downloaded image for Item ID {$item->id}");
                } else {
                    $this->error("Failed to download image for Item ID {$item->id}");
                }
            } else {
                $this->info("Image for Item ID {$item->id} has already been downloaded.");
            }

            $progressBar->advance();
        }

        $progressBar->finish();
    }

    private function isImageDownloaded($imageUrl)
    {
        return Cache::has(md5($imageUrl));
    }

    private function downloadImage($url, $itemId)
    {
        $response = Http::get($url);

        if ($response->successful()) {
            // Save the image locally, you can customize this part based on your needs
            $imagePath = storage_path("app/images/{$itemId}.jpg");
            file_put_contents($imagePath, $response->body());

            // Cache the downloaded image URL
            Cache::put(md5($url), true, now()->addDays(30));

            return true;
        }

        return false;
    }
}
