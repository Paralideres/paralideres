<?php

namespace App\Http\Controllers\Api\V1;

use DB;
use App\Models\Category;
use App\Service\CommonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryCreateRequest;


class CategoryController extends Controller
{
    use CommonService;

    protected $auth;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => [
            'index',
            'show',
            'resources'
        ]]);
        $this->middleware(function($request, $next){
            $this->auth = Auth::guard('api')->user();
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Category::with('collections')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryCreateRequest $request)
    {
        $category = Category::create([
          'label' => $request->label,
          'slug' => $request->slug,
          'description' => $request->description
        ]);

        return response()->json($category, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($param)
    {
        $response = Category::where('slug', $param)->firstOrFail();
        $response->resources = $response->resources()->simplePaginate(15);
        return response()->json($response, 200);
    }

    public function resources($param)
    {
        $user_id = ($this->auth)?$this->auth->id:null;
        $response = Category::where('slug', $param)
          ->firstOrFail()
          ->resources($user_id)
          ->Paginate(15);
        return $this->setResponse($response, 'success', 'OK', '200', '', '');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryCreateRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->label = $request->label;
        $category->slug = $request->slug;
        $category->description = $request->description;
        $category->save();
        return response()->json($category, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->json(Category::findOrFail($id)->delete(), 200);
    }

}
