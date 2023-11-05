<?php

use App\Models\Code;
use App\Models\Item;
use App\Models\Story;
use App\Models\Section;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Pool;
use App\Http\Resources\CodeResource;
use App\Http\Resources\ItemResource;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\StoryResource;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\SectionResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ItemResourceDigicult;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/items/', function (Request $request) {
    $mainItem =  ($request->has('id')) ? Item::findOrFail($request->input('id')) : Item::whereNotNull('tiny_placeholder')->whereNotNull('year')->inRandomOrder()->first();
    $similarItems = $mainItem->getVisualySimilar(2);
    $youngerItem = $mainItem->getYounger();
    $similarToYoungerItem = $youngerItem->getVisualySimilar(2);
    $olderItem = $mainItem->getOlder();
    $similarToOlderItem = $olderItem->getVisualySimilar(2);
    
    return response()->json([ 
        ItemResource::collection([$similarToYoungerItem[0], $youngerItem, $similarToYoungerItem[1]]),
        ItemResource::collection([$similarItems[0], $mainItem, $similarItems[1]]),
        ItemResource::collection([$similarToOlderItem[0], $olderItem, $similarToOlderItem[1]]),
    ]);
});


Route::get('/items/{id}', function (string $id) {
    $item = Item::findOrFail($id);
    return new ItemResource($item);
});

Route::get('/next-items/{id}', function ($id, Request $request) {
    $item = Item::findOrFail($id);
    $exclude = explode(',' , $request->get('exclude', ''));
    $similiarItem = $item->getVisualySimilar(1,$exclude)->first();
    $youngerItem = $similiarItem->getYounger($exclude);
    $olderItem = $similiarItem->getOlder($exclude);
    return response()->json(
        ItemResource::collection([$youngerItem, $similiarItem, $olderItem]),
    );
});

Route::get('/similar-item/{id}', function ($id, Request $request) {
    $item = Item::findOrFail($id);
    $exclude = explode(',' , $request->get('exclude', ''));
    $similiarItem = $item->getVisualySimilar(1,$exclude)->first();
    return new ItemResource($similiarItem);
});

Route::get('/different-items/{id}', function ($id, Request $request) {
    $item = Item::findOrFail($id);
    $exclude = explode(',' , $request->get('exclude', ''));
    $youngerItem = $item->getYounger($exclude);
    $similarToYoungerItem = $youngerItem->getVisualySimilar(2);
    $olderItem = $item->getOlder($exclude);
    $similarToOlderItem = $olderItem->getVisualySimilar(2);
    return response()->json([ 
        ItemResource::collection([$similarToYoungerItem[0], $youngerItem, $similarToYoungerItem[1]]),
        ItemResource::collection([$similarToOlderItem[0], $olderItem, $similarToOlderItem[1]]),
    ]);
});

Route::get('/older-items/{id}', function ($id, Request $request) {
    $item = Item::findOrFail($id);
    $olderItem = $item->getOlder();
    $similarToOlderItem = $olderItem->getVisualySimilar(2);
    return response()->json(
        ItemResource::collection([$similarToOlderItem[0], $olderItem, $similarToOlderItem[1]]),
    );
});

Route::get('/younger-items/{id}', function ($id, Request $request) {
    $item = Item::findOrFail($id);
    $youngerItem = $item->getYounger();
    $similarToYoungerItem = $youngerItem->getVisualySimilar(2);
    return response()->json(
        ItemResource::collection([$similarToYoungerItem[0], $youngerItem, $similarToYoungerItem[1]]),
    );
});
