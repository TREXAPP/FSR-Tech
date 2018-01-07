<?php

    return [
      /*
      | Maximum size that the profile images will be stored.
      | When a user uploads an image to be used for a profile image
      | it will be cropped to a square size. If the cropped image width (and height)
      | is greater that the max_profile_image_size, it will be reduced.
      | If not, it will stay in the original size.
      |
      */
    'max_profile_image_size' => '500',
      /*
      | A listing offer from the cso can be deleted only after a certain time expires
      | After that no deletion is possible.
      | This parameter says how many HOURS before the listing expires all the offers
      | for that listing cannot be deleted anymore.
      |
      */
    'prevent_listing_delete_time' => '12',
];
