<?php

namespace FSR\Custom;

use FSR\Cso;
use FSR\Donor;
use FSR\Hub;
use FSR\Log;
use FSR\File;
use FSR\Listing;
use FSR\HubListing;
use FSR\ListingOffer;
use FSR\HubListingOffer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Intervention\Image\ImageManagerStatic as Image;

/**
 * A bundle of my own custom functions that I think will be useful to be written as methods,
 * since probably I'll be needing them in the future. Or maybe not, who knows :)
 */
class Methods
{
    public static function listing_offer_filtered_count(array $data, int $hub_listing_id)
    {
        $listing_offers = ListingOffer::where('offer_status', 'active')
                                    ->where('hub_listing_id', $hub_listing_id)
                                    ->whereHas('hub_listing', function ($query) use ($data) {
                                        $query->where('date_listed', '>=', $data['filter_date_from'])
                                            ->where('date_listed', '<=', $data['filter_date_to']);
                                    });

        if (isset($data["filter_cso_organization"])) {
            $filter_cso_organization = $data["filter_cso_organization"];
            $listing_offers = $listing_offers->whereHas('cso', function ($query) use ($filter_cso_organization) {
                $query->where('organization_id', '=', $filter_cso_organization);
            });
        }
        if (isset($data["filter_hub_organization"])) {
            $filter_hub_organization = $data["filter_hub_organization"];
            $listing_offers = $listing_offers->whereHas('hub_listing', function ($query) use ($filter_hub_organization) {
                $query->whereHas('hub', function ($query2) use ($filter_hub_organization) {
                    $query2->where('organization_id', '=', $filter_hub_organization);
                });
            });
        }

        if (isset($data["filter_product"])) {
            $filter_product = $data["filter_product"];
            $listing_offers = $listing_offers->whereHas('hub_listing', function ($query) use ($filter_product) {
                $query->where('product_id', '=', $filter_product);
            });
        }

        if (isset($data["filter_food_type"])) {
            $filter_food_type = $data["filter_food_type"];
            $listing_offers = $listing_offers->whereHas('hub_listing', function ($query) use ($filter_food_type) {
                $query->whereHas('product', function ($query2) use ($filter_food_type) {
                    $query2->where('food_type_id', '=', $filter_food_type);
                });
            });
        }

        return $listing_offers->count();
    }
    
    public static function hub_listing_offer_filtered_count(array $data, int $listing_id)
    {
        $hub_listing_offers = HubListingOffer::where('status', 'active')
                                    ->where('listing_id', $listing_id)
                                    ->whereHas('listing', function ($query) use ($data) {
                                        $query->where('date_listed', '>=', $data['filter_date_from'])
                                            ->where('date_listed', '<=', $data['filter_date_to']);
                                    });

        if (isset($data["filter_hub_organization"])) {
            $filter_hub_organization = $data["filter_hub_organization"];
            $hub_listing_offers = $hub_listing_offers->whereHas('hub', function ($query) use ($filter_hub_organization) {
                $query->where('organization_id', '=', $filter_hub_organization);
            });
        }
        if (isset($data["filter_donor_organization"])) {
            $filter_donor_organization = $data["filter_donor_organization"];
            $hub_listing_offers = $hub_listing_offers->whereHas('listing', function ($query) use ($filter_donor_organization) {
                $query->whereHas('donor', function ($query2) use ($filter_donor_organization) {
                    $query2->where('organization_id', '=', $filter_donor_organization);
                });
            });
        }

        if (isset($data["filter_product"])) {
            $filter_product = $data["filter_product"];
            $hub_listing_offers = $hub_listing_offers->whereHas('listing', function ($query) use ($filter_product) {
                $query->where('product_id', '=', $filter_product);
            });
        }

        if (isset($data["filter_food_type"])) {
            $filter_food_type = $data["filter_food_type"];
            $hub_listing_offers = $hub_listing_offers->whereHas('listing', function ($query) use ($filter_food_type) {
                $query->whereHas('product', function ($query2) use ($filter_food_type) {
                    $query2->where('food_type_id', '=', $filter_food_type);
                });
            });
        }

        return $hub_listing_offers->count();
    }

    /**
     * Contstruct a URL for the uploaded file
     *
     * @param string $filename  - get it from the model, like this: File::first()->filename
     * @param bool $absolute    - if specified, returns an absolute path. If not, relative
     * @return string
     */
    public static function getFileUrl(string $filename, bool $absolute = true)
    {
        $path = '';

        if ($absolute) {
            $path .=config('app.url');
        }

        return $path .= '/storage' . config('app.upload_path') . '/' . $filename;
    }

    /**
     * Checks if a user is approved by the administrator
     *
     * @param Cso or Donor $user
     * @return bool
     */
    public static function isUserApproved($user)
    {
        if ($user) {
            if ($user->first()->status == 'active') {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    /**
     * Crops the image to make it square
     *
     * @param string temp path to the file that was just uploaded
     * @return void
     */
    public static function fitImage($img)
    {
        $width = $img->width();
        $height = $img->height();

        //check if file is a valid image
        if ($width && $height) {
            //reduce the bigger side to be same as the smaller one
            if ($width > $height) {
                $newSize = $height;
            } else {
                $newSize = $width;
            }
        }
        if ($newSize > Config::get('constants.max_profile_image_size')) {
            $newSize = Config::get('constants.max_profile_image_size');
        }
        $img->fit($newSize);
    }

    /**
     * Converts an inputed date to a value suitable for inserting in database
     *
     * @param string $date
     * @return string
     */
    public static function convert_date_input_to_db($date)
    {
        return str_replace('T', ' ', $date);
    }
    /**
     * Converts a date from database to datetime-local type of input
     *
     * @param string $date
     * @return string
     */
    public static function convert_date_input_from_db($date)
    {
        return str_replace(' ', 'T', $date);
    }

    /**
     * handle the volunteer image upload.
     *
     * @param  Request $request
     * @param  string $input_name
     * @param  string $purpose = ''
     * @param  string $for_user_type = ''
     * @param  string $description = ''
     * @return int id of the uploaded image in the Files table
     */
    public static function handleUpload(Request $request, string $input_name, string $purpose = '', string $for_user_type = '', string $description = '')
    {
        /*
        show like this:
        http://fsr.test/storage/upload/qovEHC3FJ70FEKwWdp202jz2qjwelB8evnTgqrPg.jpeg

        */

        //$id = $this->create($data)->id;
        if ($request->hasFile($input_name)) {

                  //Methods::fitImage($request);
            $file = $request->file($input_name);
            $filename =$file->hashName();

            $directory_path = storage_path('app/public' . config('app.upload_path'));
            $file_path = $directory_path . '/' . $filename;

            if (!file_exists($directory_path)) {
                mkdir($directory_path, 666, true);
            }
            $img = Image::make($file);
            Methods::fitImage($img);
            $img->save($file_path);

            $file_id = File::create([
                      'path_to_file'  => config('app.upload_path'),
                      'filename'      => $filename,
                      'original_name' => $file->getClientOriginalName(),
                      'extension'     => $file->getClientOriginalExtension(),
                      'size'          => Storage::size('public' . config('app.upload_path') . '/' . $filename),
                      'last_modified' => Storage::lastModified('public' . config('app.upload_path') . '/' . $filename),
                      'purpose'       => $purpose,
                      'for_user_type' => $for_user_type,
                      'description'   => $description,
                  ])->id;
            return $file_id;
        } else {
            return null;
        }
    }

    /**
     * Get image url for the user display
     *
     * @param Cso or Donor $user
     * @return string
     */
    public static function get_user_image_url($user)
    {
        if ($user->profile_image_id) {
            return Methods::getFileUrl(File::find($user->profile_image_id)->filename);
        } elseif ($user->type() == 'admin') {
            return url('img/admin.png');
        } else {
            if ($user->organization->image_id) {
                return Methods::getFileUrl(File::find($user->organization->image_id)->filename);
            } else {
                if ($user->type() == 'cso') {
                    return url('img/cso.png');
                } elseif ($user->type() == 'donor') {
                    return url('img/donor.png');
                }
                return url('img/avatar5.png');
            }
        }
    }

    /**
     * Get image url for the volunteer display
     *
     * @param Volunteer $volunteer
     * @return string
     */
    public static function get_volunteer_image_url($volunteer = null)
    {
        if (!$volunteer) {
            return url('img/volunteer.png');
        }
        if ($volunteer->image_id) {
            return Methods::getFileUrl(File::find($volunteer->image_id)->filename);
        } elseif ($volunteer->organization->image_id) {
            return Methods::getFileUrl(File::find($volunteer->organization->image_id)->filename);
        } else {
            return url('img/volunteer.png');
        }
    }

    /**
     * Get image url for the donor display
     *
     * @param Donor $volunteer
     * @return string
     */
    public static function get_donor_image_url($donor)
    {
        if ($donor->profile_image_id) {
            return Methods::getFileUrl(File::find($donor->profile_image_id)->filename);
        } elseif ($donor->organization->image_id) {
            return Methods::getFileUrl(File::find($donor->organization->image_id)->filename);
        } else {
            return url('img/donor.png');
        }
    }

    /**
     * Get image url for the cso display
     *
     * @param Donor $volunteer
     * @return string
     */
    public static function get_cso_image_url($cso)
    {
        if ($cso->profile_image_id) {
            return Methods::getFileUrl(File::find($cso->profile_image_id)->filename);
        } elseif ($cso->organization->image_id) {
            return Methods::getFileUrl(File::find($cso->organization->image_id)->filename);
        } else {
            return url('img/cso.png');
        }
    }

    
    /**
     * Get image url for the hub display
     *
     * @param Hub $hub
     * @return string
     */
    public static function get_hub_image_url($hub)
    {
        if ($hub->profile_image_id) {
            return Methods::getFileUrl(File::find($hub->profile_image_id)->filename);
        } elseif ($hub->organization->image_id) {
            return Methods::getFileUrl(File::find($hub->organization->image_id)->filename);
        } else {
            return url('img/cso.png');
        }
    }

    /**
     * Get image url for the volunteer display
     *
     * @param Organization $volunteer
     * @return string
     */
    public static function get_organization_image_url($organization)
    {
        if ($organization->image_id) {
            return Methods::getFileUrl(File::find($organization->image_id)->filename);
        } else {
            return url('img/organizations.png');
        }
    }

    /**
     * Logging events
     *
     * @param string $event
     * @param int $user_id
     * @param string $user_type
     * @param string $comment = null
     * @return boolean
     */
    public static function log_event($event, $user_id, $user_type, $comment = null)
    {
        $log = new Log;
        $log->event =$event;
        $log->user_id =$user_id;
        $log->user_type =$user_type;
        $log->comment =$comment;
        try {
            $log->save();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
