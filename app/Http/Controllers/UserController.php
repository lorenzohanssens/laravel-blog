<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    //
    private const MIN_PASSWORD_LENGTH = 4;
    private const MIN_USERNAME_LENGTH = 3;
    private const MAX_USERNAME_LENGTH = 20;
    public function register(Request $request) {
        $incomingFields = $request->validate([
            'username' =>  ['required', 'min:'.self::MIN_USERNAME_LENGTH, 'max:'.self::MAX_USERNAME_LENGTH, Rule::unique('users', 'username')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:'.self::MIN_PASSWORD_LENGTH, 'confirmed'] // 4 for testing purposes
        ]);
        //password hashing is done in user Model.
        $newlyCreatedUser = User::create($incomingFields);
        auth()->login($newlyCreatedUser);
        return redirect('/')->with('success', 'Welcome, enjoy your stay!');
    }

    public function showCorrectHomepage()  {
        $isLoggedIn = auth()->check();
        if($isLoggedIn) {
            return view("homepage-feed");
        }
        else {
            return view("homepage");
        }
    }

    public function login(Request $request) {
            $incomingFields = $request->validate([
                'loginusername' =>  'required',
                'loginpassword' => 'required'
            ]);
            $username = $incomingFields['loginusername'];
            $password = $incomingFields['loginpassword']; //is this already hashed? it's not a user model, so normally it shouldn't be => but apperantly our auth function does this for us?
           
            if(auth()->attempt(['username' => $username, 'password' => $password])) {
                $request->session()->regenerate();   
                return redirect('/')->with('success', 'Successfully logged in!');            
            }
            return redirect('/')->with('failure', 'Invalid login credentials'); //Correct homepage will be shown
    }
    public function logout() {
        auth()->logout();
        return redirect('/')->with('success', 'Successfully logged out!');   ;
    }

    private function getSharedProfileData($user) {
        //TODO: better way of passing posts only for the ones that actually need it...
        $isFollowing = 0;
        if(auth()->check())
        {
            $isFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();
        }
        View::share('sharedData',  ['username' => $user->username,  'avatar' => $user->avatar, 'isFollowing' => $isFollowing, 'posts' => $user->posts()->latest()->get()]);
    }

    public function profile(User $user) {
     
        $this->getSharedProfileData($user);
     
        return view('profile-posts');
    }

    public function profileFollowers(User $user) {
 
        $this->getSharedProfileData($user);
        return view('profile-followers');
    }
    public function profileFollowing(User $user) {
        $this->getSharedProfileData($user);
        return view('profile-following');
    }


    public function showAvatarForm() {
        return view('avatar-form');
    }
    public function storeAvatar(Request $request) {
        $request->validate([
            'avatar' => 'required|image|max:8000'
        ]);

        $user = auth()->user();
        $imgName = $user->id . '-' . uniqid() . '.jpg';
        
        $image = Image::make($request->file('avatar'))->fit(120)->encode('jpg');    // ->store('public/avatars');
        Storage::put("public/avatars/". $imgName, $image);
        $oldAvatar =  $user->avatar;
        //TODO: fix delete
        Storage::delete(str_replace("/storage/", "/public", $oldAvatar));
   
        $user->avatar = $imgName;
        $user->save();
        return back()->with('success', 'You look nice!');
    }
}
