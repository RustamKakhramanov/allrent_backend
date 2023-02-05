<?php

namespace App\Services\Media;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageCopyright
{
    /**
     * @var UploadedFile
     */
    private UploadedFile $file;

    /**
     * @var string|null
     */
    private ?string $description;

    public function __construct(UploadedFile $file, string $description = null)
    {
        $this->file = $file;
        $this->description = $description;
    }

    public static function createFromArray(array $data)
    {
        return new static($data['image'], $data['description'] ?? null);
    }

    /**
     * @return UploadedFile
     */
    public function getFile(): UploadedFile
    {
        return $this->file;
    }

    public function getNextFileName(): string
    {
        return Str::uuid()->toString() . "." . $this->file->guessExtension();
    }

    public function getCustomProperties(): array
    {
        return $this->description ? ['description' => $this->description] : [];
    }
}
