<?php

namespace App\Services;

use App\Models\Parameter;
use App\Models\Product;
use App\Models\ProductParameter;
use Illuminate\Http\Request;

class ParameterService
{
    public function all()
    {
        return Parameter::all();
    }

    public function getParametersWithProductValues($product, $usage)
    {
        $parameters = Parameter::all();
        $productParameters = $this->getParametersByProductAndUsageQuery($product->id, $usage->id)->get();
        foreach ($parameters as $parameter) {
            $productParameter = $productParameters->firstWhere('parameter_id', $parameter->id);
            if ($productParameter !== null) {
                $parameter->productValue = $productParameter->value;
            }
        }
        return $parameters;
    }

    public function updateProductParameters(Request $request, $product, $usage) {
        $inputs = $request->all();
        $this->getParametersByProductAndUsageQuery($product->id, $usage->id)->delete();
        foreach ($inputs as $key => $value) {
            if (!is_numeric($key) || !is_numeric($value)) {
                continue;
            }
            $productParameter = new ProductParameter;
            $productParameter->product_id = $product->id;
            $productParameter->usage_id = $usage->id;
            $productParameter->parameter_id = $key;
            $productParameter->value = $value;
            $productParameter->save();
        }
    }

    public function getProductsByParametersAndUsage($parameters, $usage) {
        $productIds = ProductParameter::where('usage_id', $usage->id)->pluck('product_id')->unique();
        foreach ($parameters as $key => $value) {
            if (!is_numeric($key) || !is_numeric($value)) {
                continue;
            }
            $validProductIds = ProductParameter::where('parameter_id', $key)->where('value', '>=', $value)->pluck('product_id')->unique();
            $productIds = $productIds->filter(function ($id) use ($validProductIds) {
                return $validProductIds->contains($id);
            });
        }
        return Product::whereIn('id', $productIds)->get();
    }

    public function getParametersByUsage($usage) {
        $parameterIds = ProductParameter::where('usage_id', $usage->id)->pluck('parameter_id')->unique();
        return Parameter::whereIn('id', $parameterIds)->get();
    }

    private function getParametersByProductAndUsageQuery($productId, $usageId) {
        return ProductParameter::where('product_id', $productId)->where('usage_id', $usageId);
    }
}
