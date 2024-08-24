<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Seller\Account\Products;

use Illuminate\Http\JsonResponse;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Repositories\ProductDownloadableLinkRepository;
use Webkul\Marketplace\Repositories\ProductDownloadableSampleRepository;
use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Product\Repositories\ProductRepository as BaseProductRepository;

class AssignProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected BaseProductRepository $baseProductRepository,
        protected ProductDownloadableLinkRepository $productDownloadableLinkRepository,
        protected ProductDownloadableSampleRepository $productDownloadableSampleRepository,
        protected ProductRepository $productRepository,
        protected SellerRepository $sellerRepository
    ) {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $product = $this->productRepository->findOneWhere([
            'product_id'            => $id,
            'marketplace_seller_id' => auth()->guard('seller')->user()->id,
        ]);

        if ($product) {
            session()->flash('error', trans('marketplace::app.shop.sellers.account.products.assign-product.already-selling'));

            return back();
        }

        $baseProduct = $this->baseProductRepository->find($id);

        abort_if(! $baseProduct, 404);

        if (! $this->sellerRepository->getAllowedProducts()->has($baseProduct->type)) {
            session()->flash('error', trans('marketplace::app.shop.sellers.account.products.assign-product.product-not-allowed', ['type' => $baseProduct->type]));

            return Back();
        }

        $inventorySources = core()->getCurrentChannel()->inventory_sources;

        return view('marketplace::shop.sellers.account.products.assign', compact('baseProduct', 'inventorySources', 'product', 'id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        $maxVideoFileSize = core()->getConfigData('catalog.products.attribute.file_attribute_upload_size') ?: '2048';

        $this->validate(request(), [
            'condition'          => 'required',
            'description'        => 'required',
            'images.files.*'     => ['nullable', 'mimes:bmp,jpeg,jpg,png,webp'],
            'images.positions.*' => ['nullable', 'integer'],
            'videos.files.*'     => ['nullable', 'mimetypes:application/octet-stream,video/mp4,video/webm,video/quicktime', 'max:'.$maxVideoFileSize],
            'videos.positions.*' => ['nullable', 'integer'],
        ], [
            'videos.files.*.max' => trans('marketplace::app.shop.sellers.account.products.edit.videos.error'),
        ]);

        $this->productRepository->createAssign(array_merge(request()->all(), [
            'product_id' => $id,
            'is_owner'   => 0,
        ]));

        session()->flash('success', trans('marketplace::app.shop.sellers.account.products.assign-product.create-success'));

        return redirect()->route('shop.marketplace.seller.account.products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = $this->productRepository->findorFail($id);

        $baseProduct = $product->product;

        $inventorySources = core()->getCurrentChannel()->inventory_sources;

        return view('marketplace::shop.sellers.account.products.edit-assign', compact('product', 'baseProduct', 'inventorySources'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $maxVideoFileSize = core()->getConfigData('catalog.products.attribute.file_attribute_upload_size') ?: '2048';

        $this->validate(request(), [
            'condition'          => 'required',
            'description'        => 'required',
            'images.files.*'     => ['nullable', 'mimes:bmp,jpeg,jpg,png,webp'],
            'images.positions.*' => ['nullable', 'integer'],
            'videos.files.*'     => ['nullable', 'mimetypes:application/octet-stream,video/mp4,video/webm,video/quicktime', 'max:'.$maxVideoFileSize],
            'videos.positions.*' => ['nullable', 'integer'],
        ], [
            'videos.files.*.max' => trans('marketplace::app.shop.sellers.account.products.edit.videos.error'),
        ]);

        $this->productRepository->updateAssign(request()->all(), $id);

        session()->flash('success', trans('marketplace::app.shop.sellers.account.products.assign-product.update-success'));

        return redirect()->route('shop.marketplace.seller.account.products.index');
    }

    /**
     * Uploads downloadable file
     *
     * @param  int  $id
     */
    public function uploadLink($id): JsonResponse
    {
        return new JsonResponse(
            $this->productDownloadableLinkRepository->upload(request()->all(), $id)
        );
    }

    /**
     * Uploads downloadable sample file
     *
     * @param  int  $id
     */
    public function uploadSample($id): JsonResponse
    {
        return new JsonResponse(
            $this->productDownloadableSampleRepository->upload(request()->all(), $id)
        );
    }

    /**
     * Search Products
     */
    public function searchProduct(): JsonResponse
    {
        if (request()->input('query')) {
            $results = [];

            foreach ($this->productRepository->searchProductsToAdd(request()->input('query')) as $row) {
                $results[] = [
                    'id'             => $row->product_id,
                    'sku'            => $row->sku,
                    'name'           => $row->name,
                    'price'          => core()->convertPrice($row->price),
                    'formated_price' => core()->currency($row->price),
                    'base_image'     => $row->product->base_image_url,
                ];
            }

            return new JsonResponse($results);
        } else {
            return new JsonResponse([
                'status' => 400,
            ]);
        }
    }
}
