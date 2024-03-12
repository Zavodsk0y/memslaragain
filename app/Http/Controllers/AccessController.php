<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccessRequest;
use App\Http\Resources\AccessResource;
use App\Models\File;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessController extends Controller
{
    public function index()
    {
        //
    }

    public function store(AccessRequest $request, File $file)
    {
        $userId = User::where('email', '=', $request->only('email'))->firstOrFail()->id;

        if (!$file->access->contains('id', $userId)) {
            $file->access()->attach($userId, ['type' => 'co-author']);
        }

        return AccessResource::collection($file->access()->get());
    }

    public function destroy(AccessRequest $request, File $file)
    {
        $user = User::where('email', '=', $request->only('email'))->first();
        if ($user->email === $request->user()->email) {
            throw new AuthorizationException();
        }
        $file->access()->detach($user->id);

        return AccessResource::collection($file->access()->get());
    }
}
