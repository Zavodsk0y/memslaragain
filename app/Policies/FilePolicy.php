<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function update(User $user, File $file)
    {
        return $file->isAuthor($user);
    }

    public function delete(User $user, File $file)
    {
        return $file->isAuthor($user);
    }

    public function download(User $user, File $file)
    {
        return $file->availableFor($user);
    }

    public function accessAdd(User $user, File $file)
    {
        return $file->isAuthor($user);
    }

    public function accessRemove(User $user, File $file)
    {
        return $file->isAuthor($user);
    }


}
