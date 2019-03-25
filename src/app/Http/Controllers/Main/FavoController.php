<?php

namespace App\Http\Controllers\Main;

use App\User;
use App\Model\Image;
use App\Model\Like;
use App\Http\Controllers\Controller;
use Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoController extends Controller
{
    /**
     * Show the application dashboard.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // pagination
        if (isset($request->pg)) {
            $pg = $request->pg;
        } else {
            $pg = 1;
        }

        // get images which is liked by user
        $images = Like::select('public.images.id', 'public.images.image', 'public.images.caption', 'public.images.user_id', 'public.users.github_id')
                        ->join('public.images', 'public.likes.image_id', '=', 'public.images.id')
                        ->join('public.users', 'public.images.user_id', '=', 'public.users.id')
                        ->where('public.likes.user_id', auth()->user()->id)
                        ->orderBy('public.images.id', 'desc')
                        ->offset(($pg - 1) * 10)->limit(10)
                        ->get();

        // compute max number of page
        $maxPg = ceil($images->count() / 10);

        return view('main/favo', [
            'head' => 'Favorites',
            'images' => $images,
            'pg' => $pg,
            'maxPg' => $maxPg
        ]);
    }
}