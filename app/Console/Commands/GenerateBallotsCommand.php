<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\GenerateVotingBallotJob;
use Modules\Shareholder\App\Models\Shareholder;

class GenerateBallotsCommand extends Command
{
    protected $signature = 'ballots:generate {congress_id}';
    protected $description = 'Sinh toàn bộ phiếu biểu quyết cho 1 kỳ đại hội';

    public function handle()
    {
        $congressId = $this->argument('congress_id');

        $shareholders = Shareholder::where('congress_id', $congressId)->get();

        if ($shareholders->isEmpty()) {
            $this->error("Không tìm thấy cổ đông nào cho kỳ đại hội ID = {$congressId}");
            return;
        }

        foreach ($shareholders as $shareholder) {
            GenerateVotingBallotJob::dispatch($shareholder->id);
        }

        $this->info("Đã đưa vào hàng chờ sinh phiếu cho {$shareholders->count()} cổ đông.");
    }
}
