<?php

namespace App\Contracts;

interface InsightServiceInterface
{
    public function getInsightsByCategory($category_id);
    public function getLimitedInsights();
    public function getInsightDetail($id);
    public function likeInsight($id);
    public function favouritInsight($id);
    public function getFavouritedInsights($id);
    public function allInsights();
    public function latestFavouritedInsights();
    public function allFavouritedInsights();
}
