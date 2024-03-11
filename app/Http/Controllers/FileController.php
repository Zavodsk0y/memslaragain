<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Http\Services\FileService;
use App\Models\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FileController extends Controller
{

    public function __construct(public FileService $fileService)
    {
    }

    public function index()
    {
        //
    }

    public function store(FileRequest $request): JsonResponse
    {
        $files = $request->file('files');

        $files = collect($files);

        $responses = [];

        foreach ($files as $file) {
            $response = $this->fileService->process($file);
            $responses[] = $response;
        }

        return response()->json($responses);
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
