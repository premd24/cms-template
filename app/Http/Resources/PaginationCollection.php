<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaginationCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'current_page' => $this->currentPage(),
            'total' => $this->total(),
            'per_page' => $this->perPage(),
            'last_page' => $this->lastPage(),
            'from' => $this->firstItem(),
            'to' => $this->lastItem(),
        ];
    }
}
