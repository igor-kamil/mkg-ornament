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
    $mainItem =  ($request->has('id')) ? Item::findOrFail($request->input('id')) : Item::whereNotNull('asset_id')->inRandomOrder()->first();
    $similarItems = $mainItem->getVisualySimilar(2);
    $differentItems = Item::whereNotNull('asset_id')->where('collection', 'NOT LIKE', $mainItem->collection)->inRandomOrder()->limit(2)->get();
    
    return response()->json([ 
        ItemResource::collection($differentItems[0]->getVisualySimilar(2)->push($differentItems[0])),
        ItemResource::collection([$similarItems[0], $mainItem, $similarItems[1]]),
        ItemResource::collection($differentItems[1]->getVisualySimilar(2)->push($differentItems[1])),
    ]);
});


Route::get('/items/{id}', function (string $id) {
    $item = Item::findOrFail($id);
    return new ItemResource($item);
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
    $differentItem = Item::whereNotNull('asset_id')->where('collection', 'NOT LIKE', $item->collection)->inRandomOrder()->first();
    // $similiarItem = $item->getSimilar(1,$exclude)->first();
    return response()->json(
        ItemResource::collection($differentItem->getSimilar(2)->push($differentItem)),
    );
});
