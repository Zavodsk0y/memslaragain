<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    public function toArray($request)
    {
        self::$wrap = null;

        return [
            'file_id' => $this->file_id,
            'name' => $this->name,
            'code' => 200,
            'url' => url("files/$this->file_id")
        ];
    }
}
