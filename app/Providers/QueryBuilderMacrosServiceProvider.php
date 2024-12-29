<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Query\Builder as QueryBuilder;

class QueryBuilderMacrosServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        QueryBuilder::macro('orderByOtherTable', function ($relatedTable, $parentColumn, $relatedColumn) {
            return $this->orderByDesc(function ($query) use ($relatedTable, $parentColumn, $relatedColumn) {
                $query->select('id')
                    ->from($relatedTable)
                    ->whereColumn($parentColumn, $relatedTable . '.' . $relatedColumn)
                    ->orderBy('id', 'desc')
                    ->limit(1);
            });
        });
    }
}
