<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdatePhotoRequest;
use Storage;
use Hash;

class UsersController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Users Controller
    |--------------------------------------------------------------------------
    |
    | This controller contains the page views and logic for the users section of the application.
    | The methods included in this controller, renders views to let user change their profile details, including their user password and email.
    |
    */

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * View form for uploading a new user profile photo
     */
    public function upload_photo()
    {
        return view('profile.photo');
    }


    /**
     * Store filename of user profile in database and file on server in images folder.
     * @param  UpdatePhotoRequest $request file
     */
    public function store_photo(UpdatePhotoRequest $request)
    {

        $user = User::find(\Auth::user()->id);

        $imageName = $user->id . '.' . time() . '.' . $request->file('image')->getClientOriginalExtension();

        $request->file('image')->move(
            public_path() . '/images/profile-image/', $imageName
        );

        $user->user_photo_file = $imageName;

        $user->update();

        session()->flash('flash_message', 'Your profile photo has been updated');

        return redirect('profile/upload-photo');

    }


    /**
     * Show form to let users edit their details
     */
    public function edit_details()
    {
        $user = User::find(\Auth::user()->id);

        return view('profile.form', compact('user'));
    }

    /**
     * Update the users details
     * @param  UpdateUserRequest $request validated user details input
     */
    public function update_details(UpdateUserRequest $request)
    {

        $user = User::find(\Auth::user()->id);

        $user->first_name = $request->input('first_name');

        $user->last_name = $request->input('last_name');

        $user->email = $request->input('email');

        $user->password = Hash::make($request->input('password'));

        $user->update();

        session()->flash('flash_message', 'Your details have been updated');

        return redirect('profile/my-details');
    }

}
