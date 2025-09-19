<?php

namespace App\Jobs;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Modules\Shareholder\App\Models\Shareholder;
use Modules\Congress\App\Models\Congress;

class ExportBallotsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $congressId;
    protected int $batchSize = 500;

    public function __construct(int $congressId)
    {
        $this->congressId = $congressId;
    }

    public function handle(): void
    {
        $congress = Congress::findOrFail($this->congressId);
        $slug = Str::slug($congress->name);

        $query = Shareholder::where('congress_id', $this->congressId);
        $count = $query->count();
        $batches = ceil($count / $this->batchSize);

        $dir = storage_path("app/public/ballots/{$slug}");
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        for ($i = 0; $i < $batches; $i++) {
            $shareholders = $query->skip($i * $this->batchSize)
                ->take($this->batchSize)
                ->get();

            $pdf = Pdf::loadView('congress::ballots.stand_template', [
                'shareholders' => $shareholders,
                'congress' => $congress,
            ])->setPaper('a4');

            $filename = "ballots_batch_" . ($i + 1) . ".pdf";
            $path = "{$dir}/{$filename}";
            $pdf->save($path);
        }
    }
}
