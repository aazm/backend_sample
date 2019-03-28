<?php

namespace Turing\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Turing\Helpers\EmptyDataSet;
use Turing\Http\Requests\ProductSearchRequest;
use Turing\Services\ProductServiceInterface;

class ProductController extends Controller
{
    public function search(ProductSearchRequest $request)
    {
        try {

            /** @var \Turing\Helpers\DataSet $dataSet */
            $dataSet = resolve(ProductServiceInterface::class)
                ->search($request->get('criteria', []), $request->get('offset', 0));

            if($dataSet instanceof EmptyDataSet) {
                return response()->json(['success' => false], 404);
            }

            return response()->json(array_merge(['success' => true], $dataSet->toArray()));

        } catch (\Exception $e) {

            Log::error('Product search', ['e' => $e]);
            return response(['success' => false], 500);

        }
    }
}
