<?php

namespace App\Console\Commands;

use Weaviate\Weaviate;
use Illuminate\Console\Command;

class DeleteWeaviateSchema extends Command
{
    protected $signature = 'weaviate:delete-schema';
    protected $description = 'Delete Weaviate class schema';

    protected $className = 'Ornament'; // define your own schema class name here

    public function handle() {
        $weaviate = new Weaviate(config('services.weaviate.url'), config('services.weaviate.token'));

        try {
            $response = $weaviate->schema()->deleteClass($this->className);
            $this->info("The class $this->className was deleted.");
            $this->info('Response: ' . json_encode($response));
        } catch (\Exception $e) {
            $this->info('Removing failed: ' . $e->getMessage());
        }
    }
}
