<?php

namespace App\Http\Controllers\Apis\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contracts\InsightServiceInterface;
use App\Http\Requests\Insight\InsightIdRequest;
use App\Http\Resources\Insight\InsightResource;

class InsightController extends Controller
{
    public function __construct(protected InsightServiceInterface $insightService)
    {
    }

    public function getLimitedInsights()
    {
        $data = $this->insightService->getLimitedInsights();
        $allInsight = InsightResource::collection($data['allInsights']);
        $latestInsight = InsightResource::collection($data['latestInsights']);

        return sendSuccess([
            "latestInsights" => $latestInsight,
            "allInsight" => $allInsight
        ], 'Success');
    }

    public function getInsightDetail($id)
    {
        $data = $this->insightService->getInsightDetail($id);
        $data = InsightResource::make($data);
        return sendSuccess($data, 'Insights fetched successfully');
    }

    public function likeInsight(InsightIdRequest $request)
    {
        $data = $this->insightService->likeInsight($request->insight_id);
        $data =  InsightResource::make($data);

        return sendSuccess($data, 'Success');
    }

    public function favouritInsight(InsightIdRequest $request)
    {
        $data = $this->insightService->favouritInsight($request->insight_id);
        $data =  InsightResource::make($data);

        return sendSuccess($data, 'Success');
    }

    public function getFavouritedInsights()
    {
        $latestFavouritInsite = $this->insightService->LatestFavouritedInsights();
        $limitedInsight = $this->insightService->getFavouritedInsights($latestFavouritInsite?->id);
        $limitedInsight = InsightResource::collection($limitedInsight);
        $data = InsightResource::make($latestFavouritInsite);
        return sendSuccess([
            "latest" => $latestFavouritInsite ? $data : null,
            "insights" => $limitedInsight,
        ], 'Success');
    }

    public function allInsights()
    {
        $response = $this->insightService->allInsights();
        $response = InsightResource::collection($response);
        return sendSuccess($response, 'Success', paginate($response));
    }

    public function allFavouritedInsights()
    {
        $response = $this->insightService->allFavouritedInsights();
        $response = InsightResource::collection($response);
        return sendSuccess($response, 'Success', paginate($response));
    }
}
