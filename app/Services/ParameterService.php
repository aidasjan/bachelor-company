<?php

namespace App\Services;

use App\Models\Parameter;
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

    private function getParametersByProductAndUsageQuery($productId, $usageId) {
        return ProductParameter::where('product_id', $productId)->where('usage_id', $usageId);
    }
}
