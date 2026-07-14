<?php

namespace App\Http\Controllers;

use App\Http\Requests\SampleItemRequest;
use App\Http\Resources\PaginationCollection;
use App\Http\Resources\SampleItemResource;
use App\Http\Utils\ApiResponse;
use App\Models\SampleItem;
use Illuminate\Http\Request;

class SampleItemController extends Controller
{
    /**
     * Display a listing of the items.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = SampleItem::query();

                // 1. Search
                if ($request->has('search') && ! empty($request->input('search.value'))) {
                    $search = $request->input('search.value');
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('description', 'LIKE', "%{$search}%");
                    });
                }

                // 2. Sort
                $sortColumn = 'created_at';
                $sortDirection = 'desc';
                if ($request->has('order')) {
                    $order = $request->input('order.0');
                    if ($order) {
                        $colIdx = $order['column'];
                        $colData = $request->input("columns.{$colIdx}.data");
                        $allowed = ['id', 'name', 'status', 'created_at'];
                        if (in_array($colData, $allowed)) {
                            $sortColumn = $colData;
                            $sortDirection = $order['dir'] === 'asc' ? 'asc' : 'desc';
                        }
                    }
                }

                // 3. Pagination
                $length = max(1, (int) $request->input('length', 10));
                $start = max(0, (int) $request->input('start', 0));
                $page = (int) floor($start / $length) + 1;

                $paginator = $query->orderBy($sortColumn, $sortDirection)
                    ->paginate($length, ['*'], 'page', $page);

                return response()->json([
                    'draw' => intval($request->draw),
                    'success' => true,
                    'message' => 'Items retrieved successfully.',
                    'data' => [
                        'data' => SampleItemResource::collection($paginator),
                        'pagination' => (new PaginationCollection($paginator))->resolve(),
                    ],
                ]);
            } catch (\Exception $e) {
                return ApiResponse::error($e->getMessage());
            }
        }

        return view('pages.sample-items.index');
    }

    /**
     * Show the form for creating a new item.
     */
    public function create()
    {
        return view('pages.sample-items.create');
    }

    /**
     * Store a newly created item in storage.
     */
    public function store(SampleItemRequest $request)
    {
        try {
            $item = SampleItem::create($request->validated());

            return ApiResponse::success('Item created successfully!', new SampleItemResource($item), 201);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to create item: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit($id)
    {
        $item = SampleItem::findOrFail($id);
        return view('pages.sample-items.create', compact('item'));
    }

    /**
     * Update the specified item in storage.
     */
    public function update(SampleItemRequest $request, $id)
    {
        try {
            $item = SampleItem::findOrFail($id);
            $item->update($request->validated());

            return ApiResponse::success('Item updated successfully!', new SampleItemResource($item), 200);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to update item: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified item from storage.
     */
    public function destroy($id)
    {
        try {
            $item = SampleItem::findOrFail($id);
            $item->delete();

            return ApiResponse::success('Item deleted successfully!', [], 200);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to delete item: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Toggle the status of the item.
     */
    public function toggleStatus($id)
    {
        try {
            $item = SampleItem::findOrFail($id);
            $item->status = $item->status === 'active' ? 'inactive' : 'active';
            $item->save();

            return ApiResponse::success('Status updated successfully!', new SampleItemResource($item), 200);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to toggle status: ' . $e->getMessage(), 500);
        }
    }
}
