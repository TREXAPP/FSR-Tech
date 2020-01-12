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
Route::get('login', function () {
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
      case 'hub':
          return redirect(route('hub.home'));
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
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/', 'Auth\LoginController@login');
Route::get('logout', function () {
    return redirect(route('home'));
})->name('logout');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');
  //get organizations with ajax
Route::post('register/organizations', 'Auth\RegisterController@getOrganizations')->name('register.organizations');
  //get organization address with ajax
Route::post('register/organizations/address/{id}', 'Auth\RegisterController@getAddress')->name('register.address');

//Free volunteers routes ...
Route::get('free_volunteers', 'Auth\FreeVolunteersController@index')->name('free_volunteers');
Route::post('free_volunteers', 'Auth\FreeVolunteersController@handle_post')->name('free_volunteers');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

//Email confirm routes...
Route::get('email/confirm/{token}', 'Auth\EmailConfirmController@showConfirmForm')->name('email.confirm');
Route::get('email/resend_link', 'Auth\EmailConfirmController@resend_link')->name('email.resend_link');

//master admin
Route::get('master_admin/admins', 'MasterAdmin\AdminsController@index')->name('master_admin.admins');
Route::post('master_admin/admins', 'MasterAdmin\AdminsController@handle_post')->name('master_admin.admins');
Route::get('master_admin/admins/new', 'MasterAdmin\NewAdminController@index')->name('master_admin.new_admin');
Route::post('master_admin/admins/new', 'MasterAdmin\NewAdminController@handle_post')->name('master_admin.new_admin');
Route::get('master_admin/admins/{admin_id}', 'MasterAdmin\EditAdminController@index')->name('master_admin.edit_admin');
Route::post('master_admin/admins/{admin_id}', 'MasterAdmin\EditAdminController@handle_post')->name('master_admin.edit_admin');

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

Route::get('admin/donor_listings', 'Admin\DonorListingsController@index')->name('admin.donor_listings');
Route::post('admin/donor_listings', 'Admin\DonorListingsController@handle_post')->name('admin.donor_listings');
//ajax get products
Route::post('admin/donor_listings/edit/products', 'Admin\EditDonorListingController@getProducts')->name('admin.edit_donor_listing.products');
//ajax get quantity_types on product change
Route::post('admin/donor_listings/edit/quantity_types', 'Admin\EditDonorListingController@getQuantityTypes')->name('admin.edit_donor_listing.quantity_types');
Route::get('admin/donor_listings/edit/{id}', 'Admin\EditDonorListingController@index')->name('admin.edit_donor_listing');
Route::post('admin/donor_listings/edit/{id}', 'Admin\EditDonorListingController@handle_post')->name('admin.edit_donor_listing');
Route::get('admin/hub_listing_offers/{id}', 'Admin\HubListingOfferController@index')->name('admin.hub_listing_offer');
Route::post('admin/hub_listing_offers/{id}', 'Admin\HubListingOfferController@handle_post')->name('admin.hub_listing_offer');

Route::get('admin/hub_listings', 'Admin\HubListingsController@index')->name('admin.hub_listings');
Route::post('admin/hub_listings', 'Admin\HubListingsController@handle_post')->name('admin.hub_listings');
//ajax get products
Route::post('admin/hub_listings/edit/products', 'Admin\EditHubListingController@getProducts')->name('admin.edit_hub_listing.products');
//ajax get quantity_types on product change
Route::post('admin/hub_listings/edit/quantity_types', 'Admin\EditHubListingController@getQuantityTypes')->name('admin.edit_hub_listing.quantity_types');
Route::get('admin/hub_listings/edit/{id}', 'Admin\EditHubListingController@index')->name('admin.edit_hub_listing');
Route::post('admin/hub_listings/edit/{id}', 'Admin\EditHubListingController@handle_post')->name('admin.edit_hub_listing');
Route::get('admin/cso_listing_offers/{id}', 'Admin\CsoListingOfferController@index')->name('admin.cso_listing_offer');
Route::post('admin/cso_listing_offers/{id}', 'Admin\CsoListingOfferController@handle_post')->name('admin.cso_listing_offer');


Route::get('admin/users/approve', 'Admin\ApproveUsersController@index')->name('admin.approve_users');
Route::post('admin/users/approve', 'Admin\ApproveUsersController@handle_post')->name('admin.approve_users');
Route::get('admin/users/cso', 'Admin\CsoUsersController@index')->name('admin.cso_users');
Route::post('admin/users/cso', 'Admin\CsoUsersController@handle_post')->name('admin.cso_users');
Route::get('admin/users/cso/{cso_id}', 'Admin\EditCsoUserController@index')->name('admin.edit_cso_user');
Route::post('admin/users/cso/{cso_id}', 'Admin\EditCsoUserController@handle_post')->name('admin.edit_cso_user');
Route::get('admin/users/donor', 'Admin\DonorUsersController@index')->name('admin.donor_users');
Route::post('admin/users/donor', 'Admin\DonorUsersController@handle_post')->name('admin.donor_users');
Route::get('admin/users/donor/{donor_id}', 'Admin\EditDonorUserController@index')->name('admin.edit_donor_user');
Route::post('admin/users/donor/{donor_id}', 'Admin\EditDonorUserController@handle_post')->name('admin.edit_donor_user');
Route::post('admin/users/cso/{cso_id}', 'Admin\EditCsoUserController@handle_post')->name('admin.edit_cso_user');
Route::get('admin/users/hub', 'Admin\HubUsersController@index')->name('admin.hub_users');
Route::post('admin/users/hub', 'Admin\HubUsersController@handle_post')->name('admin.hub_users');
Route::get('admin/users/hub/{hub_id}', 'Admin\EditHubUserController@index')->name('admin.edit_hub_user');
Route::post('admin/users/hub/{hub_id}', 'Admin\EditHubUserController@handle_post')->name('admin.edit_hub_user');


Route::get('admin/organizations/new', 'Admin\NewOrganizationController@index')->name('admin.new_organization');
Route::post('admin/organizations/new', 'Admin\NewOrganizationController@handle_post')->name('admin.new_organization');
Route::get('admin/organizations/cso', 'Admin\CsoOrganizationsController@index')->name('admin.cso_organizations');
Route::post('admin/organizations/cso', 'Admin\CsoOrganizationsController@handle_post')->name('admin.cso_organizations');
Route::get('admin/organizations/donor', 'Admin\DonorOrganizationsController@index')->name('admin.donor_organizations');
Route::post('admin/organizations/donor', 'Admin\DonorOrganizationsController@handle_post')->name('admin.donor_organizations');
Route::get('admin/organizations/hub', 'Admin\HubOrganizationsController@index')->name('admin.hub_organizations');
Route::post('admin/organizations/hub', 'Admin\HubOrganizationsController@handle_post')->name('admin.hub_organizations');
Route::get('admin/organizations/{organization_id}', 'Admin\EditOrganizationController@index')->name('admin.edit_organization');
Route::post('admin/organizations/{organization_id}', 'Admin\EditOrganizationController@handle_post')->name('admin.edit_organization');

Route::get('admin/food_types', 'Admin\FoodTypesController@index')->name('admin.food_types');
Route::post('admin/food_types', 'Admin\FoodTypesController@handle_post')->name('admin.food_types');
Route::get('admin/food_types/new', 'Admin\NewFoodTypeController@index')->name('admin.new_food_type');
Route::post('admin/food_types/new', 'Admin\NewFoodTypeController@handle_post')->name('admin.new_food_type');
Route::get('admin/food_types/{food_type_id}', 'Admin\EditFoodTypeController@index')->name('admin.edit_food_type');
Route::post('admin/food_types/{food_type_id}', 'Admin\EditFoodTypeController@handle_post')->name('admin.edit_food_type');

Route::get('admin/transport_types', 'Admin\TransportTypesController@index')->name('admin.transport_types');
Route::post('admin/transport_types', 'Admin\TransportTypesController@handle_post')->name('admin.transport_types');
Route::get('admin/transport_types/new', 'Admin\NewTransportTypeController@index')->name('admin.new_transport_type');
Route::post('admin/transport_types/new', 'Admin\NewTransportTypeController@handle_post')->name('admin.new_transport_type');
Route::get('admin/transport_types/{transport_type_id}', 'Admin\EditTransportTypeController@index')->name('admin.edit_transport_type');
Route::post('admin/transport_types/{transport_type_id}', 'Admin\EditTransportTypeController@handle_post')->name('admin.edit_transport_type');

Route::get('admin/donor_types', 'Admin\DonorTypesController@index')->name('admin.donor_types');
Route::post('admin/donor_types', 'Admin\DonorTypesController@handle_post')->name('admin.donor_types');
Route::get('admin/donor_types/new', 'Admin\NewDonorTypeController@index')->name('admin.new_donor_type');
Route::post('admin/donor_types/new', 'Admin\NewDonorTypeController@handle_post')->name('admin.new_donor_type');
Route::get('admin/donor_types/{donor_type_id}', 'Admin\EditDonorTypeController@index')->name('admin.edit_donor_type');
Route::post('admin/donor_types/{donor_type_id}', 'Admin\EditDonorTypeController@handle_post')->name('admin.edit_donor_type');


Route::get('admin/products', 'Admin\ProductsController@index')->name('admin.products');
Route::post('admin/products', 'Admin\ProductsController@handle_post')->name('admin.products');
Route::get('admin/products/new', 'Admin\NewProductController@index')->name('admin.new_product');
Route::post('admin/products/new', 'Admin\NewProductController@handle_post')->name('admin.new_product');
Route::get('admin/products/{product_id}', 'Admin\EditProductController@index')->name('admin.edit_product');
Route::post('admin/products/{product_id}', 'Admin\EditProductController@handle_post')->name('admin.edit_product');


Route::get('admin/quantity_types', 'Admin\QuantityTypesController@index')->name('admin.quantity_types');
Route::post('admin/quantity_types', 'Admin\QuantityTypesController@handle_post')->name('admin.quantity_types');
Route::get('admin/quantity_types/new', 'Admin\NewQuantityTypeController@index')->name('admin.new_quantity_type');
Route::post('admin/quantity_types/new', 'Admin\NewQuantityTypeController@handle_post')->name('admin.new_quantity_type');
Route::get('admin/quantity_types/{quantity_type_id}', 'Admin\EditQuantityTypeController@index')->name('admin.edit_quantity_type');
Route::post('admin/quantity_types/{quantity_type_id}', 'Admin\EditQuantityTypeController@handle_post')->name('admin.edit_quantity_type');


Route::get('admin/volunteers', 'Admin\VolunteersController@index')->name('admin.volunteers');
Route::post('admin/volunteers', 'Admin\VolunteersController@handle_post')->name('admin.volunteers');
Route::get('admin/volunteers/new', 'Admin\NewVolunteerController@index')->name('admin.new_volunteer');
Route::post('admin/volunteers/new', 'Admin\NewVolunteerController@handle_post')->name('admin.new_volunteer');
Route::get('admin/volunteers/{volunteer_id}', 'Admin\EditVolunteerController@index')->name('admin.edit_volunteer');
Route::post('admin/volunteers/{volunteer_id}', 'Admin\EditVolunteerController@handle_post')->name('admin.edit_volunteer');

Route::get('admin/locations', 'Admin\LocationsController@index')->name('admin.locations');
Route::post('admin/locations', 'Admin\LocationsController@handle_post')->name('admin.locations');
Route::get('admin/locations/new', 'Admin\NewLocationController@index')->name('admin.new_location');
Route::post('admin/locations/new', 'Admin\NewLocationController@handle_post')->name('admin.new_location');
Route::get('admin/locations/{location_id}', 'Admin\EditLocationController@index')->name('admin.edit_location');
Route::post('admin/locations/{location_id}', 'Admin\EditLocationController@handle_post')->name('admin.edit_location');

Route::get('admin/regions', 'Admin\RegionsController@index')->name('admin.regions');
Route::post('admin/regions', 'Admin\RegionsController@handle_post')->name('admin.regions');
Route::get('admin/regions/new', 'Admin\NewRegionController@index')->name('admin.new_region');
Route::post('admin/regions/new', 'Admin\NewRegionController@handle_post')->name('admin.new_region');
Route::get('admin/regions/{region_id}', 'Admin\EditRegionController@index')->name('admin.edit_region');
Route::post('admin/regions/{region_id}', 'Admin\EditRegionController@handle_post')->name('admin.edit_region');


Route::get('admin/email', 'Admin\EmailController@index')->name('admin.email');
Route::post('admin/email', 'Admin\EmailController@handle_post')->name('admin.email');
  //ajax routes
Route::post('admin/email/organizations', 'Admin\EmailController@get_organizations')->name('admin.email.organizations');
Route::post('admin/email/users', 'Admin\EmailController@get_users')->name('admin.email.users');
Route::post('admin/email/counters', 'Admin\EmailController@get_counters')->name('admin.email.counters');

//resource page
Route::get('admin/resource_page_donor', 'Admin\DonorResourcePageController@index')->name('admin.resource_page_donor');
Route::post('admin/resource_page_donor', 'Admin\DonorResourcePageController@handle_post')->name('admin.resource_page_donor');
Route::get('admin/resource_page_cso', 'Admin\CsoResourcePageController@index')->name('admin.resource_page_cso');
Route::post('admin/resource_page_cso', 'Admin\CsoResourcePageController@handle_post')->name('admin.resource_page_cso');

//activity report
Route::get('admin/reports/activity', 'Admin\ActivityReportController@index')->name('admin.activity_report');
Route::post('admin/reports/activity', 'Admin\ActivityReportController@handle_post')->name('admin.activity_report');

//registration report
Route::get('admin/reports/registration', 'Admin\RegistrationReportController@index')->name('admin.registration_report');
Route::post('admin/reports/registration', 'Admin\RegistrationReportController@handle_post')->name('admin.registration_report');

//donor product-donations report
Route::get('admin/reports/donor_product_donations', 'Admin\DonorProductDonationsReportController@index')->name('admin.donor_product_donations_report');
Route::post('admin/reports/donor_product_donations', 'Admin\DonorProductDonationsReportController@handle_post')->name('admin.donor_product_donations_report');

//donor donations report
Route::get('admin/reports/donor_donations', 'Admin\DonorDonationsReportController@index')->name('admin.donor_donations_report');
Route::post('admin/reports/donor_donations', 'Admin\DonorDonationsReportController@handle_post')->name('admin.donor_donations_report');

//hub product-donations report
Route::get('admin/reports/hub_product_donations', 'Admin\HubProductDonationsReportController@index')->name('admin.hub_product_donations_report');
Route::post('admin/reports/hub_product_donations', 'Admin\HubProductDonationsReportController@handle_post')->name('admin.hub_product_donations_report');

//hub donations report
Route::get('admin/reports/hub_donations', 'Admin\HubDonationsReportController@index')->name('admin.hub_donations_report');
Route::post('admin/reports/hub_donations', 'Admin\HubDonationsReportController@handle_post')->name('admin.hub_donations_report');



//donor routes
Route::get('donor/home', 'Donor\HomeController@index')->name('donor.home');
Route::get('donor/profile', 'Donor\ProfileController@index')->name('donor.profile');
Route::get('donor/edit_profile', 'Donor\ProfileController@edit_profile')->name('donor.edit_profile');
Route::post('donor/edit_profile', 'Donor\ProfileController@handle_post');

Route::get('donor/new_listing', 'Donor\NewListingController@index')->name('donor.new_listing');
Route::post('donor/new_listing', 'Donor\NewListingController@handle_post');
  //get products with ajax
Route::post('donor/new_listing/products', 'Donor\NewListingController@getProducts')->name('donor.new_listing.products');
Route::post('donor/new_listing/quantity_types', 'Donor\NewListingController@getQuantityTypes')->name('donor.new_listing.quantity_types');
Route::get('donor/my_active_listings', 'Donor\MyActiveListingsController@index')->name('donor.my_active_listings');
Route::post('donor/my_active_listings', 'Donor\MyActiveListingsController@handle_post');

Route::get('donor/my_accepted_listings/{hub_listing_offer_id}', 'Donor\MyAcceptedListingsController@single_hub_listing_offer')->name('donor.single_hub_listing_offer');
Route::post('donor/my_accepted_listings/{hub_listing_offer_id}', 'Donor\MyAcceptedListingsController@single_hub_listing_offer_post')->name('donor.single_hub_listing_offer');

//resource page
Route::get('donor/resource_page', 'Donor\ResourcePageController@index')->name('donor.resource_page');
//change password
Route::get('/donor/change_password', 'Donor\ChangePasswordController@index')->name('donor.change_password');
Route::post('/donor/change_password', 'Donor\ChangePasswordController@handle_post')->name('donor.change_password');


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
Route::post('cso/active_listings/get_volunteer', 'Cso\ActiveListingsController@get_volunteer')->name('cso.active_listings.get_volunteer');

Route::get('cso/accepted_listings', 'Cso\AcceptedListingsController@index')->name('cso.accepted_listings');
Route::post('cso/accepted_listings', 'Cso\AcceptedListingsController@handle_post');
Route::post('cso/accepted_listings/update_volunteer', 'Cso\AcceptedListingsController@update_volunteer')->name('cso.accepted_listings.update_volunteer');
Route::get('cso/accepted_listings/{listing_offer_id}', 'Cso\AcceptedListingsController@single_accepted_listing')->name('cso.accepted_listings.single_accepted_listing');
Route::post('cso/accepted_listings/{listing_offer_id}', 'Cso\AcceptedListingsController@single_accepted_listing_post')->name('cso.accepted_listings.single_accepted_listing');

// hub routes
Route::get('hub/home', 'Hub\HomeController@index')->name('hub.home');
Route::get('hub/profile', 'Hub\ProfileController@index')->name('hub.profile');
Route::get('hub/edit_profile', 'Hub\ProfileController@edit_profile')->name('hub.edit_profile');
Route::post('hub/edit_profile', 'Hub\ProfileController@handle_post');

Route::get('/hub/change_password', 'Hub\ChangePasswordController@index')->name('hub.change_password');
Route::post('/hub/change_password', 'Hub\ChangePasswordController@handle_post')->name('hub.change_password');

Route::get('hub/donor_listings', 'Hub\DonorListingsController@index')->name('hub.donor_listings');
Route::post('hub/donor_listings', 'Hub\DonorListingsController@handle_post');

Route::get('hub/accepted_listings', 'Hub\AcceptedListingsController@index')->name('hub.accepted_listings');
Route::post('hub/accepted_listings', 'Hub\AcceptedListingsController@handle_post');
Route::get('hub/accepted_listings/{hub_listing_offer_id}', 'Hub\AcceptedListingsController@single_accepted_listing')->name('hub.accepted_listings.single_accepted_listing');
Route::post('hub/accepted_listings/{hub_listing_offer_id}', 'Hub\AcceptedListingsController@single_accepted_listing_post')->name('hub.accepted_listings.single_accepted_listing');


Route::get('hub/hub_listings', 'Hub\HubListingsController@index')->name('hub.hub_listings');
Route::post('hub/hub_listings', 'Hub\HubListingsController@handle_post');

Route::get('hub/new_hub_listing', 'Hub\NewHubListingController@index')->name('hub.new_hub_listing');
Route::post('hub/new_hub_listing', 'Hub\NewHubListingController@handle_post');
  //get products with ajax
Route::post('hub/new_hub_listing/products', 'Hub\NewHubListingController@getProducts')->name('hub.new_hub_listing.products');
Route::post('hub/new_hub_listing/quantity_types', 'Hub\NewHubListingController@getQuantityTypes')->name('hub.new_hub_listing.quantity_types');

Route::get('hub/active_listings', 'Hub\ActiveListingsController@index')->name('hub.active_listings');
Route::post('hub/active_listings', 'Hub\ActiveListingsController@handle_post');

Route::get('hub/cso_accepted_listings/{listing_offer_id}', 'Hub\CsoAcceptedListingsController@single_listing_offer')->name('hub.single_listing_offer');
Route::post('hub/cso_accepted_listings/{listing_offer_id}', 'Hub\CsoAcceptedListingsController@single_listing_offer_post')->name('hub.single_listing_offer');

// TODO hub routes

//resource page
Route::get('cso/resource_page', 'Cso\ResourcePageController@index')->name('cso.resource_page');
//change password
Route::get('/cso/change_password', 'Cso\ChangePasswordController@index')->name('cso.change_password');
Route::post('/cso/change_password', 'Cso\ChangePasswordController@handle_post')->name('cso.change_password');
