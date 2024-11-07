<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $path;
    protected $width;
    protected $height;
    protected $quality;

    public function __construct($path, $width, $height, $quality)
    {
        $this->path = public_path($path);
        $this->width = $width;
        $this->height = $height;
        $this->quality = $quality;
    }

    public function handle()
    {
        $sourceImage = imagecreatefromjpeg($this->path);
        $originalWidth = imagesx($sourceImage);
        $originalHeight = imagesy($sourceImage);

        // Redimensionnement proportionnel
        $ratio = min($this->width / $originalWidth, $this->height / $originalHeight);
        $newWidth = $originalWidth * $ratio;
        $newHeight = $originalHeight * $ratio;

        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

        imagejpeg($newImage, $this->path, $this->quality);

        imagedestroy($sourceImage);
        imagedestroy($newImage);
    }
}
