<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ParameterService;
use App\Services\ProductService;
use App\Services\UsageService;

class RecommendationsController extends Controller
{

    public function __construct(ProductService $productService, UsageService $usageService, ParameterService $parameterService)
    {
        $this->productService = $productService;
        $this->usageService = $usageService;
        $this->parameterService = $parameterService;
    }

    public function showParameters(Request $request)
    {
        $this->validateShowParametersRequest($request);
        $usage = $this->usageService->find($request->input('usage'));
        if ($usage === null) {
            abort(404);
        }
        $parameters = $this->parameterService->getParametersByUsage($usage);
        return view('pages.products.recommendations.parameters')->with(['parameters' => $parameters, 'usage' => $usage]);
    }

    public function show(Request $request, $usageId)
    {
        $usage = $this->usageService->find($usageId);
        if ($usage === null) {
            abort(404);
        }
        $products = $this->productService->getProductsByParametersAndUsage($request->all(), $usage);
        $data = array(
            'products' => $products,
            'headline' => __('main.search_results')
        );
        return view('pages.products.index')->with($data);
    }

    private function validateShowParametersRequest($request)
    {
        $this->validate($request, [
            'usage' => 'required|numeric',
        ]);
    }
}
