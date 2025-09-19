<?php

namespace Modules\Vote\App\Services;

use Modules\Vote\App\Repositories\VoteContentRepository;

class VoteContentService
{
    public function __construct(protected VoteContentRepository $voteContentRepository)
    {
    }

    public function getListVotes()
    {
        return $this->voteContentRepository->getListVotes();
    }

    public function createVoteContent($data)
    {
        return $this->voteContentRepository->createVoteContent($data);
    }

    public function findVoteContentById($id)
    {
        return $this->voteContentRepository->findVoteContentById($id);
    }

    public function updateVoteContent($id, $data)
    {
        return $this->voteContentRepository->updateVoteContent($id, $data);
    }
}
