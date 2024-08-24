<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Seller\Account\Products;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Requests\ProductForm;
use Webkul\Admin\Http\Resources\AttributeResource;
use Webkul\Attribute\Repositories\AttributeFamilyRepository;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Core\Rules\Slug;
use Webkul\Marketplace\DataGrids\Shop\ProductDataGrid;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\Marketplace\Repositories\SellerCategoryRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Product\Helpers\ProductType;
use Webkul\Product\Repositories\ProductDownloadableLinkRepository;
use Webkul\Product\Repositories\ProductDownloadableSampleRepository;
use Webkul\Product\Repositories\ProductRepository as BaseProductRepository;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected AttributeFamilyRepository $attributeFamilyRepository,
        protected BaseProductRepository $baseProductRepository,
        protected CategoryRepository $categoryRepository,
        protected ProductDownloadableLinkRepository $productDownloadableLinkRepository,
        protected ProductDownloadableSampleRepository $productDownloadableSampleRepository,
        protected ProductRepository $productRepository,
        protected SellerRepository $sellerRepository,
        protected SellerCategoryRepository $sellerCategoryRepository
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(ProductDataGrid::class)->toJson();
        }

        return view('marketplace::shop.sellers.account.products.index');
    }

    /**
     * Search Products To assign.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search()
    {
        $results = [];

        foreach ($this->productRepository->searchProducts(request()->input('query')) as $row) {
            $results[] = [
                'id'              => $row->product_id,
                'sku'             => $row->sku,
                'name'            => $row->name,
                'price'           => core()->convertPrice($row->price),
                'formatted_price' => $row->getTypeInstance()->getPriceHtml(),
                'base_image'      => $row->product->base_image_url,
            ];
        }

        return new JsonResponse($results);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $configurableFamily = null;

        $seller = auth()->guard('seller')->user();

        $requiredFields = ['shop_title', 'address1', 'phone', 'postcode', 'city', 'state', 'country'];

        foreach ($requiredFields as $field) {
            if (empty($seller[$field])) {
                session()->flash('warning', trans('marketplace::app.shop.sellers.account.products.create.update-profile'));

                return Back();
            }
        }

        if ($familyId = request()->get('family')) {
            $configurableFamily = $this->attributeFamilyRepository->find($familyId);
        }

        return view('marketplace::shop.sellers.account.products.create')
            ->with([
                'families'                  => $this->attributeFamilyRepository->all(),
                'configurableFamily'        => $configurableFamily,
                'sellerAllowedProductTypes' => $this->sellerRepository->getAllowedProducts(),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'type'                => 'required',
            'attribute_family_id' => 'required',
            'sku'                 => ['required', 'unique:products,sku', new Slug],
            'super_attributes'    => 'array|min:1',
            'super_attributes.*'  => 'array|min:1',
        ]);

        if (
            ProductType::hasVariants(request()->input('type'))
            && ! request()->has('super_attributes')
        ) {
            $configurableFamily = $this->attributeFamilyRepository->find(request()->input('attribute_family_id'));

            return response()->json([
                'attributes' => AttributeResource::collection($configurableFamily->configurable_attributes),
            ]);
        }

        if (! $this->sellerRepository->getAllowedProducts()->has(request()->input('type'))) {
            return response()->json([
                'message' => trans('marketplace::app.shop.sellers.account.products.product-type-not-allowed', ['type' => request()->input('type')]),
            ]);
        }

        Event::dispatch('catalog.product.create.before');

        $product = $this->baseProductRepository->create(request()->only([
            'type',
            'attribute_family_id',
            'sku',
            'super_attributes',
            'family',
        ]));

        Event::dispatch('catalog.product.create.after', $product);

        $this->productRepository->create([
            'product_id' => $product->id,
            'is_owner'   => 1,
        ]);

        session()->flash('success', trans('marketplace::app.shop.sellers.account.products.create-success'));

        return response()->json([
            'redirect_url' => route('marketplace.account.products.edit', $product->id),
        ]);
    }

    /**
     * Search simple products.
     */
    public function searchSimple(): JsonResponse
    {
        $results = [];

        request()->query->add([
            'status'               => null,
            'visible_individually' => null,
            'name'                 => request('query'),
            'sort'                 => 'created_at',
            'order'                => 'desc',
        ]);

        $products = $this->productRepository->searchFromDatabase(auth()->guard('seller')->user());

        foreach ($products as $product) {
            $results[] = [
                'id'              => $product->id,
                'sku'             => $product->sku,
                'name'            => $product->name,
                'price'           => $product->price,
                'formatted_price' => core()->formatBasePrice($product->price),
                'images'          => $product->images,
                'inventories'     => $product->inventories,
            ];
        }

        $products->setCollection(collect($results));

        return new JsonResponse($products);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $seller = auth()->guard('seller')->user();

        $sellerProduct = $this->productRepository->findOneWhere([
            'product_id'            => $id,
            'marketplace_seller_id' => $seller->id,
        ]);

        if (! $sellerProduct?->is_owner) {
            return redirect()->route('marketplace.account.products.assign.edit', $sellerProduct->id);
        } elseif ($sellerProduct) {
            $product = $this->baseProductRepository->with([
                'variants' => [
                    'inventories',
                ],
            ])->findorFail($id);

            $sellerCategory = $this->sellerCategoryRepository->findOneByField('seller_id', $seller->id);

            $categories = $this->categoryRepository
                ->whereIn('id', $sellerCategory?->categories ?: [])
                ->get()
                ->toTree();

            return view('marketplace::shop.sellers.account.products.edit')
                ->with([
                    'product'          => $product,
                    'sellerProduct'    => $sellerProduct,
                    'categories'       => $categories,
                    'inventorySources' => core()->getCurrentChannel()->inventory_sources,
                ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Webkul\Product\Http\Requests\ProductForm  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductForm $request, $id)
    {
        $sellerProducts = $this->productRepository->getMarketplaceProductByProduct($id);

        abort_if(! $sellerProducts, 401);

        request()->merge([
            'locale'     => core()->getRequestedLocaleCode(),
            'additional' => [
                'seller_id' => auth()->guard('seller')->user()->id,
            ],
        ]);

        Event::dispatch('catalog.product.update.before', $id);

        $product = $this->baseProductRepository->update(request()->all(), $id);

        Event::dispatch('catalog.product.update.after', $product);

        $this->productRepository->update(request()->all(), $sellerProducts->id);

        session()->flash('success', trans('marketplace::app.shop.sellers.account.products.update-success'));

        return redirect()->route('shop.marketplace.seller.account.products.index');
    }

    /**
     * Uploads downloadable file.
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
     * Uploads downloadable sample file.
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id): JsonResponse
    {
        try {
            $product = $this->productRepository->find($id);

            if ($product->is_owner) {
                $this->baseProductRepository->delete($product->product_id);
            } else {
                $this->productRepository->delete($id);
            }

            return new JsonResponse([
                'message' => trans('marketplace::app.shop.sellers.account.products.delete-success'),
            ], 200);
        } catch (Exception $e) {
            report($e);

            return new JsonResponse([
                'message' => trans('marketplace::app.shop.sellers.account.products.delete-failed'),
            ], 500);
        }
    }

    /**
     * Mass Delete the products
     *
     * @return response
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $products = $this->productRepository
            ->findWhereIn('id', $massDestroyRequest->input('indices'));

        $this->productRepository
            ->whereIn('id', $products->where('is_owner', 0)->pluck('id'))
            ->delete();

        $this->baseProductRepository
            ->whereIn('id', $products->where('is_owner', 1)->pluck('product_id'))
            ->delete();

        return new JsonResponse([
            'message' => trans('marketplace::app.shop.sellers.account.products.delete-success'),
        ], 200);
    }
}
