<?php

namespace Modules\Vote\App\Repositories;

use Modules\Vote\App\Models\VoteContent;
use Yajra\DataTables\Facades\DataTables;

class VoteContentRepository
{
    public function getListVotes()
    {
        $query = VoteContent::query();

        return DataTables::of($query)
            ->addColumn('action', function ($vote_content) {
                return view('vote::vote_content.partials.actions', [
                    'id' => $vote_content->id,
                    'name' => $vote_content->title
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function createVoteContent($data)
    {
        return VoteContent::create($data);
    }

    public function findVoteContentById($id)
    {
        return VoteContent::find($id);
    }

    public function updateVoteContent($id, $data)
    {
        $vote_content = $this->findVoteContentById($id);

        if (!$vote_content) {
            return null;
        }
        $vote_content->update($data);
        return $vote_content;
    }

    public function deleteVoteContent($id)
    {
        $vote_content = $this->findVoteContentById($id);
        if (!$vote_content) {
            return false;
        }
        return $vote_content->delete();
    }
}
