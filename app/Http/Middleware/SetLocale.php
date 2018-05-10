<?php

namespace App\Http\Middleware;

use Closure;
use App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $referrer_lang = explode('?lang=', request()->server('HTTP_REFERER'));
        App::setLocale('en');
        
        if($request->lang) {
            App::setLocale($request->lang);
        }
        else if(count($referrer_lang) > 1) {
            App::setLocale($referrer_lang[1]);
        }
        

        return $next($request);
    }
}
