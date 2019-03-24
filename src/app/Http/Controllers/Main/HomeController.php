<?php

namespace App\Http\Controllers\Main;

use App\Model\Image;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
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

        // $images = Image::select('public.images.id', 'public.images.image', 'public.images.caption', 'public.images.user_id', 'public.users.github_id')
        //                 ->join('public.users', 'public.images.user_id', '=', 'public.users.id')
        //                 ->orderBy('public.images.id', 'desc')
        //                 ->offset(($pg - 1) * 10)->limit(10)
        //                 ->get();

        // get all images
        $images = Image::orderBy('id', 'desc')
                        ->offset(($pg - 1) * 10)->limit(10)
                        ->get();

        // compute max number of page
        $maxPg = ceil(Image::count() / 10);

        return view('main/home', [
            'head' => 'Latest',
            'images' => $images,
            'pg' => $pg,
            'maxPg' => $maxPg
        ]);
    }

    /**
     * Show the application dashboard.
     * 
     * @return \Illuminate\Http\Response
     */
    public function ranking(Request $request)
    {
        // pagination
        if (isset($request->pg)) {
            $pg = $request->pg;
        } else {
            $pg = 1;
        }
        
        // get images which have 0 like
        $info1 = Image::select('public.images.id', 'image', 'caption', 'public.images.user_id', DB::raw('0'))
                        ->whereNotIn('public.images.id', function ($query) {
                            $query->select('public.images.id')
                            ->from('public.likes')
                            ->join('public.images', 'public.likes.image_id', '=', 'public.images.id')
                            ->groupBy('public.images.id');
                        });

        // get images which have any likes, and merge before
        $info2 = Image::select('public.images.id', 'image', 'caption', 'public.images.user_id', DB::raw('count(public.images.id)'))
                        ->join('public.likes', 'public.images.id', '=', 'image_id')
                        ->groupBy('public.images.id')
                        ->union($info1)
                        ->orderBy('count', 'desc')
                        ->offset(($pg - 1) * 10)->limit(10)
                        ->get();

        $images = $info2;

        // compute max number of page
        $maxPg = ceil(Image::count() / 10);

        return view('main/home', [
            'head' => 'Ranking',
            'images' => $images,
            'pg' => $pg,
            'maxPg' => $maxPg
        ]);
    }
}
