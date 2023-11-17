<?php

namespace App\Console\Commands;

use Weaviate\Weaviate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Weaviate\Exceptions\WeaviateException;

class CreateWeaviateSchema extends Command
{
    protected $signature = 'weaviate:create-schema';
    protected $description = 'Create Weaviate class schema';
    
    protected $className = 'Ornament'; // define your own schema class name here

    public function handle()
    {
        $weaviate = new Weaviate(config('services.weaviate.url'), config('services.weaviate.token'));

        try {
            $existingClass = $weaviate->schema()->get($this->className);
            $this->info("The schema for class $this->className already exists.");
        } catch (\Exception $e) {
            $schema = [
                'class' => $this->className,
                'description' => 'Images of different objects featuring ornamental details',
                'moduleConfig' => [
                    'img2vec-neural' => [
                        'imageFields' => ['image'],
                    ],
                ],
                'vectorIndexType' => 'hnsw',
                'vectorizer' => 'img2vec-neural',
                'properties' => [
                    [
                        'name' => 'identifier',
                        'dataType' => ['string'],
                        'tokenization' => 'field',
                        'description' => 'id of the item',
                    ],
                    [
                        'name' => 'image',
                        'dataType' => ['blob'],
                        'description' => 'image',
                    ],
                ]
            ];

            $response = $weaviate->schema()->createClass($schema);
            $this->info('The schema has been defined.');
        }
    }
}
