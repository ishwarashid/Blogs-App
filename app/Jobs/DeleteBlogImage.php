<?php

namespace App\Jobs;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class DeleteBlogImage implements ShouldQueue
{
    use Queueable;

    protected $publicId;

    /**
     * Create a new job instance.
     */
    public function __construct($publicId)
    {
        $this->publicId = $publicId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $result = Cloudinary::uploadApi()->destroy($this->publicId, ['invalidate' => true]);
        // $result = cloudinary()->uploadApi()->destroy($publicId, ['invalidate' => true]);


        if ($result['result'] !== 'ok') {
            Log::error('Failed to delete image: ' . $this->publicId);
        }
    }
}
