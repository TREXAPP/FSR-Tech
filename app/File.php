<?php

namespace FSR;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'path_to_file',
    'filename',
    'original_name',
    'extension',
    'size',
    'last_modified',
    'purpose',
    'for_user_type',
    'description',
  ];
}
