<?php



namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Tag;



class ExtraController {

    public function create(Request $request)
    {
        // $request->user()
        $input = $request->input();
        $tag = new Tag();
        $tag->label = $input['tag'];
        $tag->slug = str_replace(' ', '_', $input['tag']);
        $tag->created_at = Carbon::now();
        $tag->save();
        $rv = array(
            "status" => 2000,
            "tag" => $tag->toArray()
        );
        return json_encode($rv, true);
    }
}