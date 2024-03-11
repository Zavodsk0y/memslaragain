<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file_id',
        'url',
        'user_id',
    ];

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $primaryKey = 'file_id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'files';
}
