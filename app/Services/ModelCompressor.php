<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class ModelCompressor
{
    /**
     * Compress a GLB file using gltf-transform.
     * Applies Draco compression and WebP texture compression.
     *
     * @param string $inputPath Absolute path to the original .glb file
     * @param string $outputPath Absolute path to the destination .glb file
     * @return bool True if successful, false otherwise
     */
    public function compress(string $inputPath, string $outputPath): bool
    {
        $npxPath = env('NPX_PATH', 'npx');
        
        $command = [
            $npxPath,
            'gltf-transform',
            'optimize',
            $inputPath,
            $outputPath,
            '--compress', 'draco',
            '--texture-compress', 'webp'
        ];

        try {
            $process = new Process($command);
            $process->setWorkingDirectory(base_path());
            // Compression can take a while for large files
            $process->setTimeout(300); 
            $process->run();

            if (!$process->isSuccessful()) {
                Log::error('ModelCompressor failed: ' . $process->getErrorOutput());
                return false;
            }

            return file_exists($outputPath);
        } catch (\Exception $e) {
            Log::error('ModelCompressor exception: ' . $e->getMessage());
            return false;
        }
    }
}
