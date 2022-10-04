<?php

declare(strict_types=1);

namespace Companypost\Traits;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Storage;
use Illuminate\Support\Facades\File;

trait StorageImageTrait
{
    public function storageTraitUpload($request, $fieldName, $foderName)
    {
        if ($request->hasFile($fieldName)) {
            $file = $request->$fieldName;

            $fileNameOrigin = $file->getClientOriginalName();

            $fileNameHash = Str::random(10) . '.' . $file->getClientOriginalExtension();

            $filePath = $request->file($fieldName)->storeAs('public/' . $foderName, $fileNameHash); 

            $dataUploadTrait = [
                'file_name' => $fileNameOrigin,
                'file_path' => Storage::url($filePath)
            ];

            return $dataUploadTrait;
        }

        return null;
    }

    public function unlinkImage($imagePath)
    {
        if (File::exists($imagePath)) {
            File::delete($imagePath);

            return true;
        }

        return false;
    }
}
