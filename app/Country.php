<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Country extends Model
{
    public function users()
    {
    	return $this->hasMany('App\User');
    }

    public function addresses()
    {
        return $this->hasMany('App\Address');
    }

    public function getNameAttribute($value)
    {
    	$language = App::isLocale('zh') ? 'zho' : 'eng';
    	$translated = $this->code_iso !== '-' && $this->code_iso !== '' 
    				? country($this->code)->getTranslation($language)['common'] 
    				: $value;

    	return $translated ;
    }

    public function getCodeAttribute()
    {
    	return $this->code_iso == "-" ? $this->code_fips : $this->code_iso;
    }

    public function getDefaultLocaleAttribute(){
        return country($this->code)->getLanguages();
    }
    
}
