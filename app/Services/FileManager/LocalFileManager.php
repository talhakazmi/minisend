<?php

namespace App\Services\FileManager;

use Illuminate\Support\Facades\Storage;

class LocalFileManager implements FileManagerInterface
{
    private function getSpaceName()
    {
        return 'public';
    }

    private function getDisk()
    {
        return Storage::disk($this->getSpaceName());
    }

    public function getPath($path)
    {
        return '/' . $path;
    }


    public function getUrl($path)
    {
        return $this->getDisk()->url($this->getPath($path));
    }

    public function storeImage($file, $path)
    {
        $this->getDisk()->put($this->getPath($path), file_get_contents($file), 'public');
    }

    public function delete($path)
    {
        if ($this->getDisk()->exists($this->getPath($path))) {
            $this->getDisk()->delete($this->getPath($path));
        }
    }
}
