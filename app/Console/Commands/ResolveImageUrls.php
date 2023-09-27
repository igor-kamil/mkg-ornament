<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ResolveImageUrls extends Command
{
    protected $signature = 'resolve:image-urls';
    protected $description = 'Resolve image URLs for already imported items';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $chunkSize = 100;

        $totalItems = Item::whereNull('image_src')->count();
        $this->output->progressStart($totalItems);

        Item::whereNull('image_src')->chunk($chunkSize, function ($items) {
            foreach ($items as $item) {
                $imageUrl = 'https://digicult-web.digicult-verbund.de/entity-resources/images/digicult-web-mkg/1/' . $item->id . '.jpg';

                // Check if the image URL exists
                $response = Http::head($imageUrl);

                if ($response->header('Content-Type') === 'image/jpeg') {
                    $item->update(['image_src' => $imageUrl]);
                    // $this->info("Resolved image URL for item: {$item->id}");
                }
                $this->output->progressAdvance();
            }
        });

        $this->output->progressFinish();
        $this->info('Image URL resolution completed.');
    }
}
