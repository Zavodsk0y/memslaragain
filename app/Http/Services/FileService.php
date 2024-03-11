<?php

namespace App\Http\Services;

use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FileService
{
    public function process($file)
    {
        $validator = Validator::make($file, [
            'title' => 'required|mimes:doc,pdf,docx,zip,jpeg,jpg,png',
        ]);

        $fileName = $file->getClientOriginalName();

        $existingFile = File::where('user_id', Auth::id())
            ->where('name', $fileName)
            ->first();

        if ($existingFile) {
            $fileName = $this->makeUniqueFileName($fileName);
        }

        $pathInfo = pathinfo($fileName);
        $extension = $pathInfo['extension'];
        $fileId = Str::random(10);

        $file->storeAs('uploads', "$fileId.$extension");

        File::create([
            'file_id' => $fileId,
            'name' => $fileName,
            'user_id' => Auth::id(),
            'url' => url("http://tmxgjzk-m3.wsr.ru/public/api/files/$fileId")
        ]);

        $data[] = [
            'file_id' => $fileId,
            'name' => $fileName,
            'user_id' => Auth::id(),
            'url' => url("http://tmxgjzk-m3.wsr.ru/public/api/files/$fileId")
        ];

        return $data;
    }

    protected function makeUniqueFileName($fileName): string
    {
        $counter = 1;

        do {
            $pathInfo = pathinfo($fileName);
            $newFileName = $pathInfo['filename'] . " ($counter)." . $pathInfo['extension'];

            $existingFile = File::where('name', $newFileName)
                ->where('user_id', Auth::id())
                ->first();

            $counter++;
        } while ($existingFile);

        return $newFileName;
    }
}
