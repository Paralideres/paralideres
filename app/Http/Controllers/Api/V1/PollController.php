<?php

namespace App\Http\Controllers\Api\V1;

use DB;
use Carbon\Carbon;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use App\Service\CommonService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Poll\PollCreateRequest;
use App\Http\Requests\Poll\PollVoteRequest;
use Illuminate\Support\Facades\Auth;

class PollController extends Controller
{
    use CommonService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->middleware('auth:api', ['except' => [
           'index',
           'show',
           'last'
       ]]);
    }

    public function index()
    {
        return response()->json(Poll::simplePaginate(10));
    }

    public function show($id)
    {
        return response()->json(Poll::with('options')->findOrFail($id), 200);
    }

    public function last(Request $request)
    {
        $monthStart = new Carbon('first day of this month');
        $monthEnd = new Carbon('last day of this month');
        $poll = Poll::with('options')
            ->orderBy('created_at', 'asc')
            ->where('active', '=', '1')
            ->whereDate('date_from', '>=', $monthStart->toDateString())
            ->whereDate('date_to', '<=', $monthEnd->toDateString())
            ->first();

        if(!$poll){
            $poll = Poll::with('options')
                ->orderBy('created_at', 'desc')
                ->where('active', '=', '1')->first();
        }

        return $this->setResponse($poll, 'success', 'OK', '200', '', '');
    }

    public function store(PollCreateRequest $request)
    {
        $poll = Poll::create([
          'question' => $request->question,
          'date_from' => $request->date_from,
          'date_to' => $request->date_to,
          'active' => $request->active
        ]);

        $options = array_map(function($option, $key) {
            return new PollOption([
                'option' => $option,
                'index' => $key
            ]);
        }, $request->options, array_keys($request->options));

        $poll->options()->saveMany($options);

        $poll->load('options');

        return response()->json($poll, 200);
    }

    public function update(PollCreateRequest $request, $id)
    {
        $poll = Poll::find($id);

        $poll->question = $request->question;
        $poll->date_from = $request->date_from;
        $poll->date_to = $request->date_to;
        $poll->active = $request->active;

        $poll->options()->delete();

        $options = array_map(function($option, $key) {
            return new PollOption([
                'option' => $option,
                'index' => $key
            ]);
        }, $request->options, array_keys($request->options));

        $poll->options()->saveMany($options);
        $poll->save();
        $poll->load('options');

        return response()->json($poll, 200);
    }

    public function vote(PollVoteRequest $request, $id)
    {

        $hasVoted = Auth::user()->pollVote()->where([
            'poll_id' => $id,
//            'poll_options_id' => $request->option
        ])->first();

        if ($hasVoted) {

        } else {
            $pollVote = new PollVote([
                'poll_id' => $id,
                'poll_options_id' => $request->option
            ]);
            Auth::user()->pollVote()->save($pollVote);
        }

        $poll = Poll::where('id',$id)->get()->toArray();
        $poll = $poll[0];
        $poll['options'] = array();
        $options = PollOption::where('poll_id',$id)->get()->toArray();
        foreach ($options as $p){
            $eachOption = $p;
            $eachOption['votes'] = array();
            $eachOption['votes']['poll_options_id'] = $p['id'];
            $eachOption['votes']['total'] = PollVote::where('poll_id',$id)
                ->where('poll_options_id',$p['id'])
                ->count();
            $eachOption['votes']['poll_options_id'] = $p['id'];
            $poll['options'][] = $eachOption;
        }
        $rv = array(
            "status" => 3000,
            "poll" => $poll,
            "has" => isset($hasVoted->poll_options_id) ? $hasVoted->poll_options_id : $request->option
        );
        return $this->setResponse($rv, 'done', 'OK', '200', 'Vota el éxito', 'Gracias! Tu voto ha sido enviado');
    }

    public function result($id) {
        $poll = Poll::with('options.votes', 'options.votes.user')->findOrFail($id);
        return $this->setResponse($poll, 'success', 'OK', '200', 'Vota el éxito', 'Gracias! Tu voto ha sido enviado');
    }

}
