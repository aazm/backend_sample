<?php

namespace Turing\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Turing\Helpers\EmptyDataSet;
use Turing\Http\Requests\ProductSearchRequest;
use Turing\Services\ProductServiceInterface;

class ProductController extends Controller
{
    /**
     * Searches products in db
     *
     *
     * @param ProductSearchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
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

        } catch (\Throwable $e) {

            Log::error('Product search', ['e' => $e]);
            return response()->json(['success' => false], 500);

        }
    }

    /**
     * Show product by given id
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {

            return response()->json([
                'success' => true,
                'product' => resolve(ProductServiceInterface::class)->getById($id)
            ]);

        } catch (ModelNotFoundException $e) {

            return response()->json(['success' => false], 404);

        } catch (\Throwable $e) {

            Log::error('Product show', ['e' => $e]);
            return response()->json(['success' => false], 500);

        }
    }

}
