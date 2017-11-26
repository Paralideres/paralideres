<?php

namespace App\Http\Controllers\Web;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class WebController extends Controller
{
    protected $auth;


    public function __construct()
    {
        $this->middleware(function($request, $next){
            $this->auth = Auth::user();
            view()->share('auth',$this->auth);
            if($this->auth){
                $popup_categories = Category::orderBy('id','desc')->get();
                $popup_tags = Tag::orderBy('id','desc')->get();
                view()->share('popup_categories', $popup_categories);
                view()->share('popup_tags', $popup_tags);
            }
            return $next($request);
        });
    }

}
