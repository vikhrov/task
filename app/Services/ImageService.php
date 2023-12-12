<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageService
{
    public function processImage($request, $model, $fieldName = 'photo')
    {
        if ($request->hasFile($fieldName)) {
            $uploadedFile = $request->file($fieldName);
            $fileName = $this->generateFileName($uploadedFile->getClientOriginalName());

            $this->storeImage($uploadedFile, $fileName);
            $this->updateModelWithImage($model, $fieldName, $fileName);

            return true; // Успешное сохранение изображения
        }

        return false; // Нет изображения для сохранения
    }

    private function generateFileName($originalName)
    {
        return time() . '_' . $originalName;
    }

    private function storeImage($uploadedFile, $fileName)
    {
        try {
            $image = Image::make($uploadedFile)
                ->fit(300, 300, function ($constraint) {
                    $constraint->upsize();
                })
                ->orientate()
                ->encode('jpg', 80);

            Storage::put("public/photos/$fileName", $image);

            Log::info("Image stored successfully: $fileName");

        } catch (\Exception $e) {
            Log::error("Error storing image: " . $e->getMessage());
            throw $e;
        }
    }

    private function updateModelWithImage($model, $fieldName, $fileName)
    {
        $model->update([$fieldName => $fileName]);

        // Добавим эту строку для возврата имени файла
        return $fileName;
    }
}
