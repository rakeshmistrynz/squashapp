<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Mail;

Route::get('/', 'WelcomeController@index');

Route::get('/login', 'WelcomeController@index');

//********************************************************************//
//Booking Routes
//********************************************************************//


Route::get('bookings/my-bookings', 'BookingsController@list_bookings');

Route::get('bookings/book-a-court', 'BookingsController@create_booking');

Route::post('bookings/book-a-court/store-booking', 'BookingsController@store_booking');

Route::post('bookings/book-a-court/delete-booking', 'BookingsController@destroy_booking');

//Ajax Routes
Route::post('bookings/get_timeslots', 'BookingsController@get_timeslots');

Route::post('bookings/booking_check', 'BookingsController@booking_check');

Route::get('bookings/players', 'BookingsController@get_users');


//********************************************************************//
//Ladder Route
//********************************************************************//

Route::get('ladder/my-matches', 'LadderController@view_matches');

Route::get('ladder/rules', 'LadderController@view_rules');

Route::get('ladder/leader-board', 'LadderController@leader_board');

Route::get('ladder/player-profile/{id}', 'LadderController@view_profile');

//POST ROUTES
Route::post('ladder/store-result', 'LadderController@save_result');

//Ajax Routes
Route::post('ladder/get_ladder', 'LadderController@get_ladder');

Route::post('ladder/player-profile/get_profile', 'LadderController@get_profile');

//********************************************************************//
//User Route
//********************************************************************//

Route::get('profile/my-details', 'UsersController@edit_details');

Route::get('profile/upload-photo', 'UsersController@upload_photo');

//POST ROUTES
Route::post('profile/update-details', 'UsersController@update_details');

Route::post('profile/store-photo', 'UsersController@store_photo');

//********************************************************************//
//Notice Route
//********************************************************************//

Route::get('notifications/club-notices', 'NoticesController@index');

Route::get('notifications/test', 'NoticesController@test');

//********************************************************************//
//Admin Route
//********************************************************************//

Route::get('administrator/block-bookings', 'AdministratorController@block_list');

Route::get('administrator/create-block-bookings', 'AdministratorController@create_booking');

Route::get('administrator/register-member', 'AdministratorController@add_user');

Route::get('administrator/edit-member', 'AdministratorController@edit_user');

Route::get('administrator/data-member', 'AdministratorController@get_user_data');

Route::get('administrator/remove-member', 'AdministratorController@delete_user_view');

Route::get('administrator/create-notice', 'AdministratorController@create_notice');

Route::get('administrator/view-notice/{id}', 'AdministratorController@view_notice');

Route::get('administrator/notices', 'AdministratorController@view_notices');

//POST ROUTES
Route::post('administrator/get_timeslots', 'BookingsController@get_timeslots');

Route::post('administrator/store-block-booking', 'AdministratorController@store_block_bookings');

Route::post('administrator/remove-block-booking', 'AdministratorController@destroy_block_booking');

Route::post('administrator/save-user', 'AdministratorController@save_user');

Route::post('administrator/delete-user', 'AdministratorController@delete_user');

Route::post('administrator/update-user', 'AdministratorController@update_user');

Route::post('administrator/save-notice', 'AdministratorController@save_notice');

Route::post('administrator/edit-notice/{id}', 'AdministratorController@edit_notice');

Route::post('administrator/delete-notice', 'AdministratorController@delete_notice');

//********************************************************************//
    //Email Route
//********************************************************************//

Route::get('email/booking-confirmation', 'EmailController@booking_confirmation');

Route::get('email/booking-cancellation', 'EmailController@booking_cancellation');

Route::get('email/admin-booking-cancellation', 'EmailController@admin_booking_cancellation');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);