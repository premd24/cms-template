<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataTablePaginationResource extends JsonResource
{
    protected $filteredRecords;

    protected $perPage;

    protected $start;

    public function __construct($resource, $filteredRecords, $perPage, $start)
    {
        parent::__construct($resource);
        $this->filteredRecords = $filteredRecords;
        $this->perPage = $perPage;
        $this->start = $start;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $lastPage = (int) ceil($this->filteredRecords / max(1, $this->perPage));
        $currentPage = (int) ceil(($this->start / $this->perPage) + 1);

        return [
            'pagination' => [
                'current_page' => $currentPage,
                'total' => $this->filteredRecords,
                'per_page' => (int) $this->perPage,
                'last_page' => $lastPage,
                'from' => $this->start + 1,
                'to' => min($this->start + $this->perPage, $this->filteredRecords),
            ],
            'links' => [
                'first' => url()->current().'?page=1',
                'last' => url()->current().'?page='.$lastPage,
                'prev' => $currentPage > 1 ? url()->current().'?page='.($currentPage - 1) : null,
                'next' => $currentPage < $lastPage ? url()->current().'?page='.($currentPage + 1) : null,
            ],
        ];
    }
}
