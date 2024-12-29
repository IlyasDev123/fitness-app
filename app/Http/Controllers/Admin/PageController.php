<?php

namespace App\Http\Controllers\Admin;

use App\Models\CustomPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Page\UpdateRequest;

class PageController extends Controller
{
    public function getAll()
    {
        $pages = CustomPage::get();
        return sendSuccess($pages, 'All pages');
    }

    public function get($id)
    {
        $page = CustomPage::find($id);
        return sendSuccess($page, 'Page');
    }

    public function create(Request $request)
    {
        $page = new CustomPage();
        $page->title = $request->title;
        $page->description = $request->description;
        $page->type = $request->type;
        $page->save();
        return sendSuccess($page, 'Page created');
    }

    public function update(UpdateRequest $request)
    {
        try {
            $page = CustomPage::find($request->id);
            $page->update([
                'title' => $request->title,
                'content' => $request->description,
            ]);
            return sendSuccess($page, 'Page updated');
        } catch (\Throwable $th) {
            return sendErrorResponse($th->getMessage());
        }
    }

    public function delete($id)
    {
        $page = CustomPage::find($id);
        $page->delete();
        return sendSuccess(null, 'Page deleted');
    }
}
