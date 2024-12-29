<?php

namespace App\Http\Controllers;

use App\Models\CustomPage;
use Illuminate\Http\Request;

class CustomPageController extends Controller
{
    public function termAndCondition()
    {
        $page = CustomPage::where('type', 1)->first();
        return sendSuccess($page, 'Term and condition page');
    }

    public function privacyPolicy()
    {
        $page = CustomPage::where('type', 2)->first();
        return sendSuccess($page, 'Privacy policy page');
    }
}
