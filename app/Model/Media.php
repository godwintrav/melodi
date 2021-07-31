<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $guarded = ['id'];
    protected $table = 'medias';

    public function channel(){
        return $this->belongsTo(Channel::class);
    }

}
