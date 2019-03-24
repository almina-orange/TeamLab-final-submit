<?php

namespace App\Http\Controllers\Main;

use App\User;
use App\Model\Image;
use App\Model\Like;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Show the application dashboard.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // get user info
        $uid = $request->uid;
        $user = User::where('id', $uid)->first();

        // pagination
        if (isset($request->pg)) {
            $pg = $request->pg;
        } else {
            $pg = 1;
        }

        // get images which are posted by user
        $images = Image::where("user_id", $uid)
                        ->orderBy("id", "desc")
                        ->offset(($pg - 1) * 10)->limit(10)
                        ->get();

        // count posts, compute max number of page
        $posts = Image::where('user_id', $uid)->count();
        $maxPg = ceil($posts / 10);

        // count all likes
        $likes = 0;
        foreach (Image::where('user_id', $uid)->get() as $d) {
            $likes += Like::where("image_id", $d->id)->count();
        }

        return view('main/user', [
            'user' => $user,
            'images' => $images,
            'pg' => $pg,
            'maxPg' => $maxPg,
            'likes' => $likes,
            'posts' => $posts
        ]);
    }
}