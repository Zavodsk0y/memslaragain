<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResource;
use App\Http\Resources\SharedResource;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function index()
    {
        return SharedResource::collection(File::all());
    }

    public function store(Request $request)
    {
        return collect($request->file('files'))->map(function ($file) {
            $validator = Validator::make(['file' => $file], ['file' => 'required|mimes:zip,png,jpg,jpeg,doc,docx,pdf', 'max:2048']);

            if ($validator->fails()) {
                return [
                    'success' => false,
                    'message' => $validator->errors(),
                    'name' => $file->getClientOriginalName()
                ];
            }
            $fileId = Str::random(10);

            $path = $fileId . "." . $file->extension();

            $file->storeAs('uploads', $path);

            $new = File::create([
                'file_id' => $fileId,
                'user_id' => Auth::id(),
                'name' => $file->getClientOriginalName(),
                'url' => "files/$fileId"
            ]);

            $new->access()->attach(Auth::id(), ['type' => 'author', 'file_id' => $new->id]);

            return [
                'success' => true,
                'code' => 200,
                'message' => 'success',
            ] + (new FileResource($new))->jsonSerialize();
        });
    }

    public function shared()
    {
        return FileResource::collection(Auth::user()->shared);
    }

    public function show(File $file)
    {
        $info = pathinfo($file->name);
        $path = Storage::disk('local')->path("uploads/$file->file_id" . ".$info[extension]");

        return response()->download($path, basename($path));
    }

    public function update(Request $request, File $file)
    {
        $file->update($request->only('name'));

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'Renamed'
        ]);
    }

    public function destroy(File $file)
    {
        $file->delete();

        return response()->json([
            'success' => true,
            'code' => 200,
            'message' => 'File deleted'
        ]);
    }
}
