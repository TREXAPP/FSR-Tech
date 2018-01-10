<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//welcome
Route::get('/', function () {
    return redirect(route('home'));
})->name('welcome');
Route::get('home', function () {
    if (!empty(Auth::user())) {
        switch (Auth::user()->type()) {
      case 'cso':
          return redirect(route('cso.home'));
        break;
      case 'donor':
          return redirect(route('donor.home'));
        break;
      case 'admin':
          return redirect(route('admin.home'));
        break;

      default:
        return redirect(route('login'));
        break;
  }
    } else {
        return redirect(route('login'));
    }
})->name('home');

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', function () {
    return redirect(route('home'));
})->name('logout');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
  //get organizations with ajax
Route::post('register/organizations', 'Auth\RegisterController@getOrganizations')->name('register.organizations');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

//admin routes
Route::get('admin', function () {
    if (Auth::user()) {
        if (Auth::user()->type() == "admin") {
            return redirect(route('admin.home'));
        }
    }
    return redirect(route('admin.login'));
})->name('admin');
Route::get('admin/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
Route::post('admin/login', 'Auth\AdminLoginController@login')->name('admin.login');
Route::get('admin/logout', function () {
    return redirect(route('home'));
})->name('admin/logout');
Route::post('admin/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
Route::get('admin/home', 'Admin\HomeController@index')->name('admin.home');

//donor routes
Route::get('donor/home', 'Donor\HomeController@index')->name('donor.home');
Route::get('donor/profile', 'Donor\ProfileController@index')->name('donor.profile');
Route::get('donor/edit_profile', 'Donor\ProfileController@edit_profile')->name('donor.edit_profile');
Route::post('donor/edit_profile', 'Donor\ProfileController@handle_post');

Route::get('donor/new_listing', 'Donor\NewListingController@index')->name('donor.new_listing');
Route::post('donor/new_listing', 'Donor\NewListingController@handle_post');
  //get products with ajax
Route::post('donor/new_listing/products', 'Donor\NewListingController@getProducts')->name('donor.new_listing.products');
Route::get('donor/my_active_listings', 'Donor\MyActiveListingsController@index')->name('donor.my_active_listings');
Route::post('donor/my_active_listings', 'Donor\MyActiveListingsController@handle_post');

Route::get('donor/my_accepted_listings/{listing_offer_id}', 'Donor\MyAcceptedListingsController@single_listing_offer')->name('donor.single_listing_offer');
Route::post('donor/my_accepted_listings/{listing_offer_id}', 'Donor\MyAcceptedListingsController@single_listing_offer_post')->name('donor.single_listing_offer');



//cso routes
Route::get('cso/home', 'Cso\HomeController@index')->name('cso.home');
Route::get('cso/profile', 'Cso\ProfileController@index')->name('cso.profile');
Route::get('cso/edit_profile', 'Cso\ProfileController@edit_profile')->name('cso.edit_profile');
Route::post('cso/edit_profile', 'Cso\ProfileController@handle_post');

Route::get('cso/volunteers', 'Cso\VolunteersController@index')->name('cso.volunteers');
Route::post('cso/volunteers', 'Cso\VolunteersController@handle_post')->name('cso.volunteers');
Route::get('cso/volunteers/new', 'Cso\VolunteersController@new_volunteer')->name('cso.new_volunteer');
Route::post('cso/volunteers/new', 'Cso\VolunteersController@new_volunteer_post')->name('cso.new_volunteer');
Route::get('cso/volunteers/{volunteer_id}', 'Cso\VolunteersController@edit_volunteer')->name('cso.edit_volunteer');
Route::post('cso/volunteers/{volunteer_id}', 'Cso\VolunteersController@edit_volunteer_post')->name('cso.edit_volunteer');

Route::get('cso/active_listings', 'Cso\ActiveListingsController@index')->name('cso.active_listings');
Route::post('cso/active_listings', 'Cso\ActiveListingsController@handle_post');
Route::post('cso/active_listings/add_volunteer', 'Cso\ActiveListingsController@add_volunteer')->name('cso.active_listings.add_volunteer');
Route::post('cso/active_listings/get_volunteers', 'Cso\ActiveListingsController@get_volunteers')->name('cso.active_listings.get_volunteers');

Route::get('cso/accepted_listings', 'Cso\AcceptedListingsController@index')->name('cso.accepted_listings');
Route::post('cso/accepted_listings', 'Cso\AcceptedListingsController@handle_post');
Route::post('cso/accepted_listings/update_volunteer', 'Cso\AcceptedListingsController@update_volunteer')->name('cso.accepted_listings.update_volunteer');
Route::get('cso/accepted_listings/{listing_offer_id}', 'Cso\AcceptedListingsController@single_accepted_listing')->name('cso.accepted_listings.single_accepted_listing');
Route::post('cso/accepted_listings/{listing_offer_id}', 'Cso\AcceptedListingsController@single_accepted_listing_post')->name('cso.accepted_listings.single_accepted_listing');
