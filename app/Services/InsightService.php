<?php

namespace App\Services;

use App\Models\Insight;
use App\Contracts\InsightServiceInterface;

class InsightService implements InsightServiceInterface
{

    public function getLatestFirstFiveInsights()
    {
        return Insight::with('category', 'isLiked', 'isFavourited')->active()
            ->withCount('likes')->latest()
            ->limit(5)->get();
    }

    public function getLimitedInsights()
    {
        $response['latestInsights'] = $this->getLatestFirstFiveInsights();
        $ids =  $response['latestInsights']->pluck('id');
        $response['allInsights'] = Insight::whereNotIn('id', $ids)
            ->with('category', 'isLiked', 'isFavourited')->active()
            ->withCount('likes')->latest()
            ->limit(prePageLimit())->get();

        return $response;
    }

    public function allInsights()
    {
        $searchTerm = request()->query('search');
        return Insight::with('category', 'isLiked', 'isFavourited')->active()
            ->when($searchTerm, fn ($q) => $q->search($searchTerm))
            ->withCount('likes')->latest()
            ->paginate(prePageLimit());
    }

    public function getInsightsByCategory($category_id)
    {
    }

    public function getInsightDetail($id)
    {
        return Insight::with('category', 'isLiked', 'isFavourited')->withCount('likes')->findOrFail($id);
    }

    public function likeInsight($id)
    {
        $like =  Insight::with('category', 'isLiked', 'isFavourited')->withCount('likes')->find($id);
        $toggle = $like->likes()->toggle(auth()->id());
        $like['is_liked'] = false;
        if (isset($toggle['attached']) && $toggle['attached']) {
            $like['is_liked'] = true;
            $like['likes_count'] = $like['likes_count'] + 1;
        } else {
            $like['likes_count'] = $like['likes_count'] - 1;
        }

        return $like;
    }

    public function favouritInsight($id)
    {
        $favourit =  Insight::with('category', 'isLiked', 'isFavourited')->withCount('likes')->find($id);
        $toggle = $favourit->favourites()->toggle(auth()->id());
        // $favourit['is_favourited'] = false;
        // if (isset($toggle['attached']) && $toggle['attached']) {
        //     $favourit['isFavourited'] = true;
        // }

        return $favourit->refresh();
    }

    public function getFavouritedInsights($id)
    {
        return Insight::whereNot('id', $id)->with('category', 'isLiked', 'isFavourited')->withCount('likes')->whereHas('favourites', function ($query) {
            $query->where('user_id', auth()->id());
        })->orderByOtherTable('favourit_insight', 'insights.id', 'insight_id')->paginate(prePageLimit());
    }

    public function LatestFavouritedInsights()
    {
        return Insight::with('category', 'isLiked', 'isFavourited')->withCount('likes')->whereHas('favourites', function ($query) {
            $query->where('user_id', auth()->id());
        })->orderByOtherTable('favourit_insight', 'insights.id', 'insight_id')->first();
    }

    public function allFavouritedInsights()
    {
        $searchTerm = request()->query('search');
        return Insight::with('category', 'isLiked', 'isFavourited')->withCount('likes')->whereHas('favourites', function ($query) {
            $query->where('user_id', auth()->id());
        })->when($searchTerm, fn ($q) => $q->search($searchTerm))
            ->orderByOtherTable('favourit_insight', 'insights.id', 'insight_id')->paginate(prePageLimit());
    }
}
