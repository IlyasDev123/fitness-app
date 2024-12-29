<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faq;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Faqs\StoreRequest;

class FaqController extends Controller
{
    public function getAll()
    {
        $searchTerm = request()->query('search');
        $faqs = Faq::when($searchTerm, fn ($q) => $q->search($searchTerm))->get();
        return sendSuccess($faqs, 'All faqs');
    }

    public function get($id)
    {
        $faq = Faq::find($id);
        return sendSuccess($faq, 'Faq');
    }

    public function create(StoreRequest $request)
    {
        try {
            $faq = Faq::create([
                'question' => $request->question,
                'answer' => $request->answer,
            ]);
            return sendSuccess($faq, 'Faq created successfully');
        } catch (\Throwable $th) {
            return sendErrorResponse($th->getMessage());
        }
    }

    public function update(StoreRequest $request)
    {
        try {
            $faq = Faq::find($request->id);
            $faq->update([
                'question' => $request->question,
                'answer' => $request->answer,
            ]);
            return sendSuccess($faq, 'Faq updated successfully');
        } catch (\Throwable $th) {
            return sendErrorResponse($th->getMessage());
        }
    }

    public function delete($id)
    {
        $faq = Faq::find($id);
        $faq->delete();
        return sendSuccess(null, 'Faq deleted successfully');
    }

    public function sortFaqs()
    {
        $faqs = request()->sort_orders;
        foreach ($faqs as $key => $faq) {
            $faq = Faq::find($faq['id']);
            $faq->update(['sort_order' => $key + 1]);
        }
        return sendSuccess($faqs, "faq sorted successfully");
    }
}
