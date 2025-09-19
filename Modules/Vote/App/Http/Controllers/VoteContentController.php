<?php

namespace Modules\Vote\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Vote\App\Services\VoteContentService;

class VoteContentController extends Controller
{
    public function __construct(protected VoteContentService $voteContentService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('vote::vote_content.index');
    }

    public function list()
    {
        return $this->voteContentService->getListVotes();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vote::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->voteContentService->createVoteContent($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Vote Content created successfully'
        ]);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('vote::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('vote::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $this->voteContentService->updateVoteContent($id, $request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Vote Content updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    public function detail($id)
    {
        return $this->voteContentService->findVoteContentById($id);
    }
}
