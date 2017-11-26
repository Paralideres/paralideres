<?php

namespace App\Http\Controllers\Web;

use App\Models\Category;
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



}
