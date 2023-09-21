<?php

use App\Models\Code;
use App\Models\Collection;
use Illuminate\Http\Request;
use App\Http\Resources\CodeResource;
use App\Http\Resources\ItemResource;
use App\Http\Resources\SectionResource;
use App\Http\Resources\StoryResource;
use App\Models\Item;
use App\Models\Section;
use App\Models\Story;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
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
    $mainItem =  ($request->has('id')) ? Item::findOrFail($request->input('id')) : Item::whereNotNull('image_src')->inRandomOrder()->first();
    $similiarItems = Item::whereNotNull('image_src')->where('collection', 'LIKE', $mainItem->collection)->inRandomOrder()->take(2)->get();
    $differentItems = Item::whereNotNull('image_src')->where('collection', 'NOT LIKE', $mainItem->collection)->inRandomOrder()->take(2)->get();
    $items = collect([
        $differentItems[0],
        $similiarItems[0],
        $mainItem,
        $similiarItems[0],
        $differentItems[1],
    ]);
    return ItemResource::collection($items);
});

Route::get('/items/{id}', function (string $id) {
    $item = Item::findOrFail($id);
    return new ItemResource($item);
});

