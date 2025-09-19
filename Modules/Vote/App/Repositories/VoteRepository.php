<?php

namespace Modules\Vote\App\Repositories;

use Illuminate\Support\Facades\DB;

class VoteRepository
{
    public function getListVotes()
    {
        $query = DB::table('vote_content as vc')
        ->join('congresses as v', 'vc.vote_id', '=', 'v.id');
    }
}
