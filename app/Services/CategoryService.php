<?php

namespace App\Services;

use App\Models\Category;

class CategoryService implements \App\Contracts\CategoryServiceInterface
{

    public function getCategories()
    {
        return Category::get();
    }
}
