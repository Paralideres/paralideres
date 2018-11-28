<?php



namespace App\Http\Controllers\Web;

use App\Models\Category;
use App\Models\Resource;
use App\Models\ResourceDownload;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResourceController extends WebController
{

    /**
     * Display all resources
     * @return $this
     */
    public function index()
    {
        $data['categories'] = Category::orderBy('id','desc')->get();
        $data['tags'] = Tag::orderBy('id','desc')->get();
        return view('public.resource_list')->with($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($param)
    {

       $data['resource_header'] = true;
       $user_id = ($this->auth)?$this->auth->id:null;
       $data['resource'] = Resource::with(['tags', 'likesCount', 'user', 'downloads', 'category', 'category.resources' => function($q){
                $q->limit(4);
            }, 'like' => function($q)use($user_id){
                $q->where('user_id', $user_id);
            }])
            ->where('slug', $param)
            ->orWhere('id', $param)
            ->firstOrFail();

       $author = User::with('profile')
        ->where('id', $data['resource']->user_id)
        ->get()
        ->toArray();

       if(count($author) > 0){
           $data['author'] = $author[0];
       }

       $data['latestResources'] = Resource::with('category')->orderBy('id', 'desc')->limit(5)->get();
       $data['tags'] = Tag::orderBy('id','desc')->get();

       return view('public.resource')->with($data);
    }

    public function showCreate(){
       $data['categories'] = Category::orderBy('id','desc')->get();
       $data['tags'] = Tag::orderBy('id','desc')->get();
       return view('public.resource_create')->with($data);
    }

    public function download(Request $request)
    {
        $resource = Resource::where('id', $request->segment('2'))->firstOrFail();
        $file_path = 'resources/'.$resource->attachment;
        $exists = Storage::disk('public')->exists($file_path);

        if (!$exists) {
            return response('File Not Found', 404);
        }

        $resource_download = new ResourceDownload;
        $resource_download->user_id = $this->auth->id;
        $resource_download->resource_id = $resource->id;
        $resource_download->save();
        $download_filename = $resource->slug.$resource->attachment;
        return Storage::disk('public')->download($file_path, $download_filename);
   }

}

