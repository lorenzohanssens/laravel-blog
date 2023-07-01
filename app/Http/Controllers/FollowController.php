<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    //

    public function createFollow(User $user) {
        //cannot follow yourself
        //cannot follow someone you're already following

        if($user->id == auth()->user()->id) {
            return back()->with('failure', "Can't follow yourself!");
        }
        $existCheck = Follow::where([['user_id' , '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        if($existCheck) {
            return back()->with('failure', "Already followed!");
        }
        $newFollow = new Follow;
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followeduser = $user->id;
        $newFollow->save();
        return back()->with('success', "Successfully followed!");
    }
    public function removeFollow() {}
}
