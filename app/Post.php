<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];
    
    public function getThumbnailTableWidthAttribute()
    {
    	$hasLeftPhoto = $this->left_photo ? 1 : 0;
    	$hasMiddlePhoto = $this->middle_photo ? 1 : 0;
    	$hasRightPhoto = $this->right_photo ? 1 : 0;

    	return ($hasLeftPhoto + $hasMiddlePhoto + $hasRightPhoto) * 390;
    }
}
