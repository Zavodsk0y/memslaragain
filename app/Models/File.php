<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class File extends Model
{
    use HasFactory;

    protected static $type = 'type';

    protected $fillable = [
        'user_id',
        'url',
        'name',
        'file_id'
    ];

    public function getName()
    {
        $names = File::all()->pluck('name');

        $name = $this->name;
        if (!$names->contains($name)) return $name;

        $i = 1;
        $info = pathinfo($name);

        while ($names->contains("$info[filename] ($i).$info[extension]"))
        {
            $i++;
        }

        return "$info[filename] ($i).$info[extension]";
    }

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->name = $model->getName();
        });

        self::addGlobalScope(static::$type, function (Builder $builder) {
            $builder->where('user_id', '=', Auth::id());
        });
    }

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function access()
    {
        return $this->belongsToMany(User::class, 'accesses', 'file_id', 'user_id')
            ->withPivot('type');
    }

    public function isAuthor(User $user)
    {
        return $this->user_id === $user->id;
    }

    public function availableFor(User $user)
    {
        $this->access()->contains('id', $user->id);
    }

}
