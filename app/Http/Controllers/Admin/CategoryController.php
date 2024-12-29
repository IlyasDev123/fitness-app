<?php

namespace App\Http\Controllers\Admin;

use App\Models\Insight;
use App\Models\Category;
use App\Constants\Constants;
use Illuminate\Http\Request;
use App\Models\InsightCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateStatusRequest;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function getCategories()
    {
        $searchItem = request()->query('search');
        $categories = Category::where('type', Constants::CATEGORY_TYPE['workout'])->when($searchItem, fn ($q) => $q->search($searchItem))
            ->orderBy('sort_order', 'asc')->cursor();

        return sendSuccess($categories, "success");
    }

    public function getWorkoutCategories()
    {
        $categories = Category::where('type', Constants::CATEGORY_TYPE['workout'])->cursor();
        return sendSuccess($categories, "success");
    }

    public function getInsightCategories()
    {
        $searchItem = request()->query('search');
        $categories = Category::where('type', Constants::CATEGORY_TYPE['insight'])->when($searchItem, fn ($q) => $q->search($searchItem))
            ->orderBy('sort_order', 'asc')->cursor();

        return sendSuccess($categories, "success");
    }

    public function createCategory(StoreRequest $request)
    {
        try {
            $category = Category::create([
                'name' => $request->name,
                'type' => $request->type,
                'status' => $request->status,
            ]);
            return sendSuccess($category, "Category created successfully");
        } catch (\Exception $e) {
            return sendErrorResponse('Something went wrong' . $e->getMessage());
        }
    }

    public function updateCategory(StoreRequest $request, $id)
    {
        try {
            $category = Category::find($id);
            $category->update([
                'name' => $request->name,
                'type' => $request->type,
                'status' => $request->status,
            ]);
            return sendSuccess($category, "Category updated successfully");
        } catch (\Exception $e) {
            return sendErrorResponse('Something went wrong');
        }
    }

    public function deleteCategory($id)
    {
        try {
            $category = Category::find($id);
            $category->delete();
            return sendSuccess($category, "Category deleted successfully");
        } catch (\Exception $e) {
            return sendErrorResponse('Something went wrong');
        }
    }

    public function updateStatus(UpdateStatusRequest $request)
    {
        try {
            $category = Category::find($request->id);
            $category->update([
                'status' => $request->status,
            ]);
            return sendSuccess($category, "Category status updated successfully");
        } catch (\Exception $e) {
            return sendErrorResponse('Something went wrong');
        }
    }

    public function sortCategories()
    {
        $categories = request()->sort_orders;
        foreach ($categories as $key => $category) {
            $category = Category::find($category['id']);
            $category->update(['sort_order' => $key + 1]);
        }
        return sendSuccess($categories, "Category sorted successfully");
    }
}
