<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Exception;
use Weaviate\Weaviate;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class VisualSearchController extends Controller
{
    public function index()
    {
        return view('visual-search.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $weaviate = new Weaviate(config('services.weaviate.url'), config('services.weaviate.token'));
            $imageBase64 = base64_encode(file_get_contents($request->file('image')->path()));
            // dd($imageBase64);
            $limit = 1;
            // get weaviate Object ID
            $data = $weaviate->graphql()->get('{
                Get {
                  Ornament (
                    offset: 1
                    limit: '.$limit.'
                    nearImage: {
                        image: "'.$imageBase64.'"
                    }
                  ) {
                    identifier
                    _additional {
                      distance
                    }
                  }
                }
              }');

            $item_id= Arr::get($data, 'data.Get.Ornament.0.identifier');
            $similarItem = Item::find($item_id);

            return view('visual-search.result', [
                'uploadedImage' => $request->file('image'),
                'similarItem' => $similarItem,
            ]);
        } catch (Exception $e) {
            // Handle Weaviate API errors
            return back()->with('error', 'Error communicating with Weaviate');
        }

        return 'ok';
    }
}
