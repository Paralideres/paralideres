<?php

namespace App\Http\Controllers\Web;

use App\Models\Category;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends WebController
{

    /**
     * Display the home page
     * @return $this
     */
    public function index()
    {
        $data['categories'] = Category::selectRaw('categories.*, count(resources.id) as total_resource')
            ->join('resources', 'resources.category_id', '=', 'categories.id')
            ->groupBy('categories.id')
            ->orderBy('total_resource', 'desc')
            ->limit(4)
            ->get();
        return view('public.home')->with($data);
    }

    public function activation($token)
    {
        $rv = array();
        $userCheck = new User();
        $tokenCheck = $userCheck->where('is_active', 0)->where('activation_token', $token)->get()->toArray();
        $tokenReCheck = $userCheck->where('is_active', 1)->where('activation_token', $token)->get()->toArray();
        if(count($tokenReCheck) > 0){
            $rv = array(
                "status" => 2000,
                "msg" => 'Your account has already been activated. Please login now'
            );
        } else {
            if (count($tokenCheck) > 0) {
                $activeUser = new User();
                $activeUser->where('is_active', 0)
                    ->where('activation_token', $token)
                    ->update(['is_active' => 1]);
                $rv = array(
                    "status" => 2000,
                    "msg" => 'Congratulation! Your account has been activated successfully. Please login now'
                );
            } else {
                $rv = array(
                    "status" => 5000,
                    "msg" => 'Invalid activation token. Please try with valid token'
                );
            }
        }
        return view('auth.login')->with('data', $rv);
    }


}
