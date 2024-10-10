<?php

namespace App\Manager;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadManager
{

    private UploadedFile $file;
    private string $path;
    private string $base_folder = 'files';


    public const SUPPORTED_FILES = [
        'pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx'
    ];
    public const MAX_FILE_SIZE = 2048;


    /**
     * @return string|bool|null
     */
    final public function upload(): string|bool|null
    {
        (new ImageUploadManager())->path('files')->createDirectory();
        return Storage::disk('public')->put($this->path, $this->file);
    }

    final public function delete(string $file): bool
    {
        return Storage::disk('public')->delete($file);
    }

    /**
     * @param UploadedFile $file
     * @return $this
     */
    final public function file(UploadedFile $file): self
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    final public function path(string $path): self
    {
        $this->path = $this->base_folder . '/' . $path;
        return $this;
    }


}
