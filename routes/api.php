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
    $mainItem =  ($request->has('id')) ? Item::findOrFail($request->input('id')) : Item::has('assets')->inRandomOrder()->first();
    $similarItems = $mainItem->getSimilar(2);
    $differentItems = Item::has('assets')->where('collection', 'NOT LIKE', $mainItem->collection)->inRandomOrder()->take(2)->get();
    $items = collect([
        $differentItems[0],
        $similarItems[0],
        $mainItem,
        $similarItems[1],
        $differentItems[1],
    ]);
    return response()->json([ 
        ItemResource::collection($differentItems[0]->getSimilar(2)->push($differentItems[0])),
        ItemResource::collection($similarItems->push($mainItem)),
        ItemResource::collection($differentItems[1]->getSimilar(2)->push($differentItems[1])),
    ]);
});

Route::get('/items-digicult/', function (Request $request) {
    $mainItem =  ($request->has('id')) ? Item::findOrFail($request->input('id')) : Item::whereNotNull('image_src')->inRandomOrder()->first();
    $similarItems = Item::whereNotNull('image_src')->where('collection', 'LIKE', $mainItem->collection)->inRandomOrder()->take(2)->get();
    $differentItems = Item::whereNotNull('image_src')->where('collection', 'NOT LIKE', $mainItem->collection)->inRandomOrder()->take(2)->get();
    $items = collect([
        $differentItems[0],
        $similarItems[0],
        $mainItem,
        $similarItems[1],
        $differentItems[1],
    ]);
    return ItemResourceDigicult::collection($items);
});

Route::get('/items/{id}', function (string $id) {
    $item = Item::findOrFail($id);
    return new ItemResource($item);
});

Route::get('/similar-item/{id}', function ($id, Request $request) {
    $item = Item::findOrFail($id);
    $exclude = explode(',' , $request->get('exclude', ''));
    $similiarItem = $item->getSimilar(1,$exclude)->first();
    return new ItemResource($similiarItem);
});
