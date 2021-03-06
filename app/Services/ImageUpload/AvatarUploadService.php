<?php


namespace App\Services\ImageUpload;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager as Image;

class AvatarUploadService extends AbstractFileUploadService
{
    protected $defaultAvatarName;

    /**
     * AvatarUploadContract constructor.
     * @param string $basePath
     */
    public function __construct(string $basePath)
    {
        parent::__construct($basePath);
        $this->defaultAvatarName = config('images.default_user_avatar');
    }

    /**
     * @param UploadedFile $file
     * @param string $newName
     * @param string|null $oldName
     * @return string  $newName
     */
    public function upload(UploadedFile $file, string $newName = null, string $oldName = null) :string
    {
        if($newName === null) {
            $imageExtension = $file->getClientOriginalExtension();
            $newName = str_random(24) . uniqid('', false) . '.' . $imageExtension;
        }

        $uploadedImage = (new Image)->make($file)->resize(150,150);

        $avatarsDirectory = $this->basePath;
        $defaultAvatarName = $this->defaultAvatarName;

        Storage::put(
            $avatarsDirectory .'/' . $newName,
            (string)$uploadedImage->stream()
        ); // save new avatar

        if( $oldName !== $defaultAvatarName && $oldName !== null){
            //Storage::delete( $avatarsDirectory . '/' . $oldName ); // delete old avatar
            $this->delete($oldName);
        }

        return $newName;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function delete(string $name): bool
    {
        return Storage::delete( $this->basePath . '/' . $name ); // delete old avatar
    }
}
