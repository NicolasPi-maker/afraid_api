<?php

namespace App\Jobs;

use App\Models\Illustration;
use App\Models\Thumbnail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\File;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class TransferTmpFileToS3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $disk,
        private readonly string $storagePath,
        private readonly  string $fileUrl,
        private readonly Thumbnail | Illustration $model,
    )
    {}

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $file = file_get_contents($this->fileUrl);
            Storage::disk($this->disk)->put($this->storagePath, $file);
            $s3Url = Storage::disk($this->disk)->url($this->storagePath);
            $this->model->url = $s3Url;
            $this->model->update();
            return true;
        } catch (\Exception $e) {
            throw new \Exception('Failed to upload file to S3: ' . $e->getMessage());
        }
    }
}
