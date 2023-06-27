<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
    public function profile(User $user) {
        $userPosts = $user->posts()->latest()->get();
        return view('profile-posts', ['username' => $user->username, 'posts' => $userPosts, 'avatar' => $user->avatar]);
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
