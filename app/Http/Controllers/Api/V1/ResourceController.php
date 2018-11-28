<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Resource;
use App\Models\Category;
use App\Models\User;
use App\Models\Like;
use App\Models\Collection;
use App\Models\Tag;
use App\Models\Resource_tag;


use Carbon\Carbon;
use Storage;
use DB;
use App\Service\CommonService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Resource\ResourceCreateRequest;
use App\Http\Requests\Resource\ResourceDeleteRequest;
use App\Http\Requests\Resource\ResourceUploadRequest;
use App\Http\Requests\Resource\ResourceUpdateRequest;
use App\Http\Requests\Resource\ResourceAddToCollectionRequest;
use Illuminate\Support\Facades\Validator;

class ResourceController extends Controller
{
    use CommonService;

    protected $auth;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('auth:api', ['except' => ['index', 'search', 'show', 'file']]);
        $this->middleware(function($request, $next){
            $this->auth = Auth::guard('api')->user();
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        // Create Request to Handle this limit Type
        $limit = intval($request->limit);
        $limit = $limit > 0 && $limit < 20 ? $limit : 20;

        $user_id = ($this->auth)?$this->auth->id:null;
        $tag_id = ($request->has('tag_id'))?$request->tag_id:null;
        $tag_slug = ($request->has('tag_slug'))?$request->tag_slug:null;
        $category_id = ($request->has('category_id'))?$request->category_id:null;
        $author = ($request->has('author'))?$request->author:null;
        $author_id = 0;
        if($request->has('cat_slug') && !empty($request->cat_slug)){
            $category_id = DB::table('categories')->where('slug',$request->cat_slug)->pluck('id');
        }
        if($request->has('author') && !empty($request->author)){
            $authorInfo = DB::table('users')->select('id')->where('username',$author)->where('is_active', 1)->get('id')->toArray();
            if(count($authorInfo) > 0){
                $author_id = $authorInfo[0]->id;
            }
        }
        $search_text = ($request->has('search_text'))?$request->search_text:null;
        if($author != null){
            $resources = Resource::select('resources.*')
                ->where('resources.user_id', $author_id)
                ->with(
                    ['likesCount', 'category', 'user',
                        'like' => function($q)use($user_id){
                            $q->where('user_id', $user_id);
                        }
                    ]);
        } else {
            $resources = Resource::select('resources.*')
                ->with(
                    ['likesCount', 'category', 'user',
                        'like' => function($q)use($user_id){
                            $q->where('user_id', $user_id);
                        }
                    ]);
        }
        if(!empty($tag_id) || !empty($tag_slug)){
            $resources->join('resource_tag', 'resource_tag.resource_id', '=', 'resources.id')
                ->join('tags', 'tags.id', '=', 'resource_tag.tag_id')
                ->where(function($q)use($tag_id,$tag_slug){
                    $q->where('tags.id', $tag_id)->orWhere('tags.slug', $tag_slug);
                });
        }
        if(!empty($category_id)){
            $resources->where('resources.category_id', $category_id);
        }
        if(!empty($search_text)){
            $resources->where('resources.title', 'like', '%'.$search_text.'%');
        }
        $resources = $resources->orderBy('resources.created_at', 'desc')
            ->Paginate($limit);

        $resources->appends('tag_id', $tag_id);
        $resources->appends('tag_slug', $tag_slug);
        $resources->appends('category_id', $category_id);
        $resources->appends('search_text', $search_text);

        return $this->setResponse($resources,'success','OK','200','','');
    }

    /**
     * Home page resource search option
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        // Create Request to Handle this limit Type
//        $limit = intval($request->limit);
//        $limit = $limit > 0 && $limit < 20 ? $limit : 20;
        $pageNo = isset($request->pageNo) ? $request->pageNo : 1;
        $limit = 12;
        $skip = ($pageNo - 1)*$limit;

        $rv = array();
        $resourceModel = new Resource();
        $data = $resourceModel
            ->where('title','like','%'.$request->search.'%')
            ->take($limit)
            ->skip($skip)
            ->orderBy('created_at', 'desc')
            ->get()->toArray();
        if(count($data) > 0){
            foreach ($data as $recurso){
                $eachRecurso = $recurso;
                $eachRecurso['category'] = array();
                $CategoryModel = new Category();
                $CategoryInfo = $CategoryModel->where('id', $eachRecurso['category_id'])
                    ->get()->toArray();
                if(count($CategoryInfo) > 0){
                    $eachRecurso['category'] = $CategoryInfo[0];
                }
                $eachRecurso['user'] = array();
                $userModel = new User();
                $userInfo = $userModel->select('id','username')->where('id', $eachRecurso['user_id'])
                    ->get()->toArray();
                if(count($userInfo) > 0){
                    $userInfo[0]['image'] = null;
                    $userInfo[0]['fullname'] = null;
                    $eachRecurso['user'] = $userInfo[0];
                }
                $likeModel = new Like();
                $totalLike = $likeModel->where('resource_id', $eachRecurso['id'])->count();
                $eachRecurso['likes_count'] = array(
                    "resource_id" => $eachRecurso['id'],
                    "total" => $totalLike
                );
                $eachRecurso['like'] = array();
                $totalLike = $likeModel->where('resource_id', $eachRecurso['id'])->where('user_id', $eachRecurso['user_id'])->count();
                if($totalLike > 0){
                    $eachRecurso['like'] = array(
                        "resource_id" => $eachRecurso['id'],
                        "user_id" => $eachRecurso['user_id']
                    );
                }

                if(count($userInfo) > 0){
                    $userInfo[0]['image'] = null;
                    $userInfo[0]['fullname'] = null;
                    $eachRecurso['user'] = $userInfo[0];
                }
                $rv[] = $eachRecurso;
            }
        }
        return json_encode($rv, true);


        /*$resources = Resource::with('likesCount', 'category', 'user')
            ->where('title','like','%'.$request->search.'%')
            ->orderBy('created_at', 'desc')
            ->Paginate($limit);*/
//        return $this->setResponse($resources,'success','OK','200','','');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        if($request->has('step') && $request->step == 1){
            $request->validate([
                'title' => 'required|max:100',
                'review' => 'required|max:300',
                'category_id' => 'exists:categories,id',
            ], [], [
                'title' => 'TITULO RECURSO',
                'review' => 'RESEÑA O RESUME',
                'category_id' => 'CATEGORIA',
            ]);
            return $this->setResponse([],'success','OK','200','','');
        }
        else if($request->has('step') && $request->step == 2)
        {
            $request->validate([
                'content' => 'required',
            ], [], ['content' => 'CONTENIDO']);

            $resource = new Resource([
                'user_id' =>$this->auth->id,
                'title' => $request->title,
                'slug' => $this->toSlug($request->title) . '_' . uniqid(),
                'review' => $request->review,
                'category_id' => $request->category_id,
                'content' =>  $request->content
            ]);

            $resource->save();
            if($request->has('tag') && !empty($request->tag)){
                $tag_id = DB::table('tags')->insertGetId([
                    'label' => $request->tag,
                    'slug' => $this->toSlug($request->tag),
                ]);
                DB::table('resource_tag')->insert([
                    'resource_id' => $resource->id,
                    'tag_id' => $tag_id  
                ]);
            }
            elseif($request->has('tag_ids'))
            {
                if(is_array($request->tag_ids)){
                    $tag_ids = $request->tag_ids;
                    $tags = [];
                    foreach($tag_ids as $tag_id){
                        $tags[] = [
                            'resource_id' => $resource->id,
                            'tag_id' => $tag_id
                        ];
                    }
                    if(count($tags) > 0){
                        DB::table('resource_tag')->insert($tags);
                    }
                }
            }

            return $this->setResponse([],'success','OK','200','Mensaje de éxito','Recurso creado con éxito.');
        }
        else if($request->has('step') && $request->step == 3)
        {
            $request->validate([
                'attach' => 'required|mimes:pdf,doc,docx,ppt,pptx,rtf,txt|max:3000',
            ], [], ['ARCHIVO' => 'ARCHIVO']);

            $resource = new Resource([
                'user_id' =>$this->auth->id,
                'title' => $request->title,
                'slug' => $this->toSlug($request->title) . '_' . uniqid(),
                'review' => $request->review,
                'category_id' => $request->category_id,
            ]);

            if($request->hasFile('attach')){
                $file_name = time().'.'.$request->attach->extension();
                $request->attach->storeAs('resources', $file_name, 'public');
                $resource->attachment = $file_name;
            }

            $resource->save();

            if($request->has('tag') && !empty($request->tag)){
                $tag_id = DB::table('tags')->insertGetId([
                    'label' => $request->tag,
                    'slug' => $this->toSlug($request->tag),
                ]);
                DB::table('resource_tag')->insert([
                    'resource_id' => $resource->id,
                    'tag_id' => $tag_id  
                ]);
            }
            elseif($request->has('tag_ids'))
            {
                if(is_array($request->tag_ids)){
                    $tag_ids = $request->tag_ids;
                    $tags = [];
                    foreach($tag_ids as $tag_id){
                        $tags[] = [
                            'resource_id' => $resource->id,
                            'tag_id' => $tag_id
                        ];
                    }
                    if(count($tags) > 0){
                        DB::table('resource_tag')->insert($tags);
                    }
                }
            }

            return $this->setResponse([],'success','OK','200','Mensaje de éxito','Recurso creado con éxito.');
        }


    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function updateRecurso(Request $request)
    {
        if(Auth::check()) {
            $userInfo = Auth::user();
            $input = $request->input();
            if($request->has('step') && $request->step == 1){
                $request->validate([
                    'title' => 'required|max:100',
                    'review' => 'required|max:300',
                    'category_id' => 'exists:categories,id',
                ], [], [
                    'title' => 'TITULO RECURSO',
                    'review' => 'RESEÑA O RESUME',
                    'category_id' => 'CATEGORIA',
                ]);
                return $this->setResponse([],'success','OK','200','','');
            }
            else if($request->has('step') && $request->step == 2)
            {
                $input = $request->input();
                //=======================
                // Check
                //=======================
                $resourceModel = new Resource();
                $check = $resourceModel->where('id', $input['id'])->where('user_id', $userInfo['id'])->get()->first();
                if($check != null){
                    $removeTag = DB::table('resource_tag')->where('resource_id', $input['id'])->delete();
                    if(count($input['tag_ids']) > 0){
                        foreach ($input['tag_ids'] as $tag_id){
                            DB::table('resource_tag')->insert(
                                ['resource_id' => $input['id'], 'tag_id' => $tag_id, 'created_at' => Carbon::now()]
                            );
                        }
                    }
                    $resourceModel = new Resource();
                    $resourceModel->where('id', $input['id'])->where('user_id', $userInfo['id'])->update([
                        'category_id' => $input['category_id'],
                        'title' => $input['title'],
                        'review' => $input['review'],
                        'content' => $input['content'],
                        'attachment' => '',
                    ]);
                    $rv = array(
                        'status' => 2000,
                        'data' => 'Resource has been updated successfully'
                    );
                    return json_encode($rv, true);
                } else {
                    $rv = array(
                        'status' => 5000,
                        'data' => 'Invalid Request'
                    );
                    return json_encode($rv, true);
                }
            }
            else if($request->has('step') && $request->step == 3)
            {
                $input = $request->input();
                if($request->hasFile('attach')){
                    $request->validate([
                        'attach' => 'required|mimes:pdf,doc,docx,ppt,pptx,rtf,txt|max:3000',
                    ], [], ['ARCHIVO' => 'ARCHIVO']);
                }
                //=======================
                // Check
                //=======================
                $resourceModel = new Resource();
                $check = $resourceModel->where('id', $input['id'])->where('user_id', $userInfo['id'])->get()->first();
                if($check != null){
                    if($request->hasFile('attach')){
                        $file_name = time().'.'.$request->attach->extension();
                        $uploads = asset('storage/resources/');
                        $request->attach->move($uploads, $file_name);
                    } else {
                        $file_name = $check->attachment;
                    }
                    $removeTag = DB::table('resource_tag')->where('resource_id', $input['id'])->delete();
                    if(count($input['tag_ids']) > 0){
                        foreach ($input['tag_ids'] as $tag_id){
                            DB::table('resource_tag')->insert(
                                ['resource_id' => $input['id'], 'tag_id' => $tag_id, 'created_at' => Carbon::now()]
                            );
                        }
                    }
                    $resourceModel = new Resource();
                    $resourceModel->where('id', $input['id'])->where('user_id', $userInfo['id'])->update([
                        'category_id' => $input['category_id'],
                        'title' => $input['title'],
                        'review' => $input['review'],
                        'content' => $input['content'],
                        'attachment' => $file_name,
                    ]);
                    $rv = array(
                        'status' => 2000,
                        'data' => 'Resource has been updated successfully'
                    );
                    return json_encode($rv, true);
                } else {
                    $rv = array(
                        'status' => 5000,
                        'data' => 'Invalid Request'
                    );
                    return json_encode($rv, true);
                }
            }
        } else {
            $rv = array(
                'status' => 5000,
                'data' => 'Authentication failed.'
            );
            return json_encode($rv, true);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($param)
    {
        $resource = Resource::with('tags', 'likesCount', 'user', 'category')->where('id', $param)->orWhere('slug', $param)->firstOrFail();
        return response()->json($resource);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResourceDeleteRequest $request, $id)
    {
        $resource = Resource::find($id);

        if ($resource) {
            return response()->json($resource->delete(), 200);
        } else {
            return response()->json('Error', 500);
        }
    }

    public function update(ResourceUpdateRequest $request, $id)
    {
        $resource = Resource::find($id);
        $resource->title = $request->title;
        $resource->review = $request->review;
//        $resource->content = $request->content;
        $resource->category_id = $request->category_id;
        $resource->save();
        return response()->json($resource, 200);
    }

    public function upload(ResourceUploadRequest $request, $id)
    {
        $resource = Resource::find($id);
        $previous_attachment = $resource->attachment;

        $file = $request->file('resource');

        $extension = $file->guessExtension();
        $filename = $resource->slug . '_' . uniqid($id) . '.' . $extension;

        $path = storage_path('resources_files/');

        $file->move($path, $filename);

        if ($file) {
            $resource->attachment = $filename;
            $resource->save();

            if ($previous_attachment) {
                Storage::delete('resources_files/' . $previous_attachment);
            }

            return response()->json(['attachment' => $resource->attachment], 200);
        } else {
            return response()->json($file, 500);
        }
    }

    public function file($id, $docId)
    {
        $resource = Resource::where(['id' => $id, 'attachment' => $docId])->firstOrFail();
        $filePath = storage_path('resources_files/' . $resource->attachment);
        return response()->download($filePath);
    }

    public function like($id)
    {
        $resource = Resource::find($id);
        $like = $resource->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            $like->delete();

            return $this->setResponse('', 'unlike', 'OK', '200', '', '');

        } else {
            $new_like = new Like(['user_id' => Auth::id()]);
            $resource->likes()->save($new_like);

            return $this->setResponse('', 'like', 'OK', '200', '', '');
        }
    }

    public function addToCollection(ResourceAddToCollectionRequest $request, $id)
    {
        $resource = Resource::find($id);
        $collection = Collection::find($request->collection_id);

        $collection->resources()->save($resource);
        return response()->json('', 200);
    }

    /**
     * Snakecase a string
     * @param str $string
     * @return str snakecased string
     */
    private function toSlug($string)
    {
        return strtolower(preg_replace('/[\s-]/', '_', $string));
    }


    public function remove(Request $request){
        if(Auth::check()) {
            $userInfo = Auth::user();
            $input = $request->input();
            $validator = Validator::make($input, [
                'id' => 'required'
            ]);
            if($validator->fails()){
                $rv = array(
                    "status" => 5000,
                    "data" => $validator->messages()
                );
                return json_encode($rv, true);
            }
            //=================
            // Check
            //=================
            $resourseModel = new Resource();
            $check = $resourseModel
                ->where('id', $input['id'])
                ->where('user_id', $userInfo['id'])
                ->get()->first();
            if($check != null){
                $resourseModel = new Resource();
                $resourseModel
                    ->where('id', $input['id'])
                    ->where('user_id', $userInfo['id'])
                    ->delete();
                $rv = array(
                    'status' => 2000,
                    'data' => 'Resource has been removed successfully'
                );
                return json_encode($rv, true);

            } else {
                $rv = array(
                    'status' => 5000,
                    'data' => 'Invalid Request'
                );
                return json_encode($rv, true);
            }

        } else {
            $rv = array(
                'status' => 5000,
                'data' => 'Authentication failed.'
            );
            return json_encode($rv, true);
        }
    }

}
