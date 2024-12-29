<?php

namespace App\Http\Controllers\Admin;

use App\Models\Insight;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Insight\StoreRequest;
use App\Http\Requests\Insight\UpdateRequest;
use App\Http\Requests\Insight\UpdateStatusRequest;

class InsightController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $searchItem = request()->query('search');
        $insights = Insight::with('category:id,name')
            ->when($searchItem, fn ($q) => $q->search($searchItem))
            ->latest()->paginate(prePageLimit());
        return sendSuccess($insights, "success");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $thumbnail = storeFiles("insights/thumbnail/", $request->file('thumbnail'));
            $insight = Insight::create([
                "title" => $request->title,
                "slug" => $this->makeUniqueSlug(Str::slug($request->title)),
                "category_id" => $request->category_id,
                "short_description" => $request->short_description,
                "description" => $request->description,
                "thumbnail" => $thumbnail,
                "duration" => $request->duration,
                "status" => $request->status == "true" ? 1 : 0,
            ]);

            $insight->image()->create([
                "file" => $thumbnail,
                "type" => 1,
                "status" => true
            ]);

            DB::commit();
            return sendSuccess($insight, "Insight created successfully.");
        } catch (\Throwable $th) {
            DB::rollBack();
            $error = $th->getMessage();
            return sendErrorResponse("Something went wrong please try again later.{$error}");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $insight = Insight::with('category')->find($id);
        return sendSuccess($insight, "success");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request)
    {
        try {
            $insight = Insight::find($request->insight_id);
            $insight->update([
                "title" => $request->title,
                "category_id" => $request->category_id,
                "short_description" => $request->short_description,
                "description" => $request->description,
                "thumbnail" => $request->file('thumbnail') ? storeFiles("insights/thumbnail/", $request->thumbnail) : $insight->getRawOriginal('thumbnail'),
                "duration" => $request->duration,
            ]);

            return sendSuccess($insight, "Insight Updated Successfully.");
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            return sendErrorResponse("Something went wrong please try again later.{$error}");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $insisght =  Insight::find($id)->delete();
        return sendSuccess($insisght, "Insight has been deleted successfully.");
    }

    protected function makeUniqueSlug($baseSlug)
    {
        $slug = $baseSlug;
        $counter = 1;
        while (Insight::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function updateStatus(UpdateStatusRequest $request)
    {
        try {
            $status = $request->status == true ? 1 : 0;
            $workout = Insight::find($request->id);
            $workout->status = $status;
            $workout->save();

            return sendSuccess($workout, 'Insight status has been updated successfully.');
        } catch (\Throwable $th) {
            return sendErrorResponse($th->getMessage());
        }
    }
}
