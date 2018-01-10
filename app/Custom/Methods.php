<?php

namespace FSR\Custom;

use FSR\Cso;
use FSR\File;
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
}
