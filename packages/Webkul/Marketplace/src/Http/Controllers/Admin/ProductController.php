<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\Marketplace\DataGrids\Admin\ProductDataGrid;
use Webkul\Marketplace\Mail\ProductApprovalNotification;
use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductRepository as BaseProductRepository;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected BaseProductRepository $baseProductRepository,
        protected ProductRepository $productRepository
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(ProductDataGrid::class)->toJson();
        }

        return view('marketplace::admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id): JsonResponse
    {
        $product = $this->productRepository->findOrFail($id);

        if ($product->is_owner) {
            $this->baseProductRepository->delete($product->product_id);
        } else {
            $this->productRepository->delete($id);
        }

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.products.index.delete-success'),
        ], 200);
    }

    /**
     * Mass delete the product.
     */
    public function massDestroy(MassDestroyRequest $request): JsonResponse
    {
        $products = $this->productRepository->findWhereIn('id', $request->input('indices'));

        $this->productRepository
            ->whereIn('id', $products->where('is_owner', 0)->pluck('id'))
            ->delete();

        $this->baseProductRepository
            ->whereIn('id', $products->where('is_owner', 1)->pluck('product_id'))
            ->delete();

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.products.index.delete-success'),
        ]);
    }

    /**
     * Mass update the reviews.
     */
    public function massUpdate(MassUpdateRequest $request): JsonResponse
    {
        $this->productRepository
            ->whereIn('id', $request->input('indices'))
            ->update(['is_approved' => $request->input('value')]);

        $products = $this->productRepository
            ->with('seller')
            ->findWhereIn('id', $request->input('indices'));

        foreach ($products as $product) {
            if (! $product->seller->is_approved) {
                continue;
            }

            try {
                Mail::queue(new ProductApprovalNotification($product));
            } catch (\Exception $e) {
            }
        }

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.products.index.update-success'),
        ], 200);
    }
}
