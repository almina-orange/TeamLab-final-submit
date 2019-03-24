<?php

namespace App\Http\Controllers\Main;

use App\Model\Image;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search image.
     * 
     * @return
     */
    public function search(Request $request)
    {   
        // pagination
        if (isset($request->pg)) {
            $pg = $request->pg;
        } else {
            $pg = 1;
        }

        // search images
        $tar = $request->target;
        if (isset($request->target)) {
            $images = Image::where('caption', 'ilike', '%'.$tar.'%')
                            ->orderBy('id', 'desc')
                            ->offset(($pg - 1) * 10)->limit(10)
                            ->get();

            $maxPg = ceil(Image::where('caption', 'like', '%'.$tar.'%')->count() / 10);
        } else {
            $images = null;
            $maxPg = 1;
        }
        
        return view('main/search', [
            'target' => $tar,
            'images' => $images,
            'pg' => $pg,
            'maxPg' => $maxPg
        ]);
    }
}
