<?php

namespace App\Http\Controllers;

use App\Models\Question;
// use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\AskQuestionRequest;

class QuestionsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except'=>['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // \DB::enableQueryLog();
        $questions = Question::with('user')->latest()->paginate(5);
        return view('questions.index', compact('questions'));
        // dd(\DB::getQueryLog());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $question = new Question();
        return view('questions.create', compact('question'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AskQuestionRequest $request)
    {
        $request -> user() -> questions() -> create( $request ->only('title', 'body') );
        return redirect() -> route('questions.index') -> with('success', "Your question has been submitted");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {   
        // $question->load(['answers.user', 'answers' => function($query){
        //     $query ->orderBy('votes_count', 'DESC');
        // }]);
        $question->load(['user']);
        $question -> increment('views');
        return \view('questions.show', \compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        //$question = Question::findOrFail($id);
        // if(Gate::denies('update-question', $question))
        // {
        //     \abort(403, "Access Denied"); 
        // }
        
        $this->authorize('update', $question);
        return \view('questions.edit', \compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(AskQuestionRequest $request, Question $question)
    {
        // if(Gate::denies('update-question', $question))
        // {
        //     \abort(403, "Access Denied"); 
        // }
        $this->authorize('update', $question);
        $question->update($request->only('title', 'body'));

        if($request -> expectsJson())
        {
            return response() -> json([
                'message' => "Your Question has been updated.",
                'body_html' => $question->body_html
            ]);
        }
        return redirect('questions')->with('success', "You question has been updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        // if(Gate::denies('delete-question', $question))
        // {
        //     \abort(403, "Access Denied"); 
        // }
        $this->authorize('delete', $question);
        $question -> delete();

        if($request -> expectsJson())
        {
            return response() -> json([
                'message' => "Yor question has been deleted.",
            ]);
        }

        return \redirect('/questions')->with('success', 'Yor question has been deleted');
    }
}
