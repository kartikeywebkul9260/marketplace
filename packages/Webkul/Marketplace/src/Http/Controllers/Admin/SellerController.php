<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\Core\Rules\Slug;
use Webkul\Marketplace\DataGrids\Admin\SellerDataGrid;
use Webkul\Marketplace\DataGrids\Admin\SellerFlagDataGrid;
use Webkul\Marketplace\Enum\Order;
use Webkul\Marketplace\Http\Requests\SellerFormRequest;
use Webkul\Marketplace\Mail\NewSellerNotification;
use Webkul\Marketplace\Mail\SellerApprovalNotification;
use Webkul\Marketplace\Mail\SellerDeleteNotification;
use Webkul\Marketplace\Mail\SellerWelcomeNotification;
use Webkul\Marketplace\Repositories\OrderRepository;
use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Product\Repositories\ProductRepository as BaseProductRepository;

class SellerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected OrderRepository $orderRepository,
        protected ProductRepository $productRepository,
        protected BaseProductRepository $baseProductRepository
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(SellerDataGrid::class)->toJson();
        }

        return view('marketplace::admin.sellers.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'name'     => ['required'],
            'email'    => ['required', 'email', 'unique:marketplace_sellers,email'],
            'url'      => ['required', 'unique:marketplace_sellers,url', 'lowercase', new Slug],
        ]);

        $seller = $this->sellerRepository->create(array_merge(request()->only([
            'name',
            'email',
            'url',
        ]), [
            'password'              => bcrypt(rand(100000, 10000000)),
            'is_approved'           => ! core()->getConfigData('marketplace.settings.general.seller_approval_required'),
            'allowed_product_types' => [
                "simple",
                "configurable",
                "virtual",
                "downloadable",
            ],
        ]));

        try {
            if ($seller->is_approved) {
                Mail::queue(new SellerApprovalNotification($seller));
            }

            Mail::queue(new SellerWelcomeNotification($seller));

            Mail::to(core()->getAdminEmailDetails()['email'])
                ->send(new NewSellerNotification($seller));
        } catch (\Exception $e) {
        }

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.sellers.index.create.success'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        if (request()->ajax()) {
            return app(SellerFlagDataGrid::class)->toJson();
        }

        $seller = $this->sellerRepository->findOrFail($id);

        return view('marketplace::admin.sellers.edit', compact('seller'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     */
    public function update(SellerFormRequest $request, $id): JsonResponse
    {
        $this->sellerRepository->findOrFail($id);

        $data = $request->validated();

        if (empty($data['commission_enable'])) {
            $data['commission_enable'] = 0;
            $data['commission_percentage'] = 0;
        }

        if (empty($data['allowed_product_types'])) {
            $data['allowed_product_types'] = null;
        }

        $this->sellerRepository->update($data, $id);

        session()->flash('success', trans('marketplace::app.admin.sellers.edit.update-success'));

        return new JsonResponse([
            'redirect_url' => route('admin.marketplace.sellers.index'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id): JsonResponse
    {
        $orderCount = $this->orderRepository
            ->where('marketplace_seller_id', $id)
            ->whereIn('status', [
                Order::STATUS_PENDING->value,
                Order::STATUS_PROCESSING->value,
            ])
            ->count();

        if ($orderCount) {
            return new JsonResponse([
                'message' => trans('marketplace::app.admin.sellers.index.pending-orders'),
            ], 500);
        }

        try {
            $seller = $this->sellerRepository->find($id);

            try {
                Mail::to($seller->email)->send(new SellerDeleteNotification($seller));
            } catch (\Exception $e) {
            }

            $seller->delete();

            return new JsonResponse([
                'message' => trans('marketplace::app.admin.sellers.index.delete-success'),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('marketplace::app.admin.sellers.index.delete-failed'),
            ], 500);
        }
    }

    /**
     * Mass delete the sellers.
     */
    public function massDestroy(MassDestroyRequest $request): JsonResponse
    {
        $orderCount = $this->orderRepository
            ->WhereIn('marketplace_seller_id', $request->input('indices'))
            ->whereIn('status', [
                Order::STATUS_PENDING->value,
                Order::STATUS_PROCESSING->value,
            ])
            ->count();

        if ($orderCount) {
            return new JsonResponse([
                'message' => trans('marketplace::app.admin.sellers.index.pending-orders'),
            ], 500);
        }

        $sellers = $this->sellerRepository->findWhereIn('id', $request->input('indices'));

        $this->sellerRepository->whereIn('id', $request->input('indices'))->delete();

        foreach ($sellers as $seller) {
            try {
                Mail::to($seller->email)->send(new SellerDeleteNotification($seller));
            } catch (\Exception $e) {
            }
        }

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.sellers.index.delete-success'),
        ]);
    }

    /**
     * Mass update the reviews.
     */
    public function massUpdate(MassUpdateRequest $request): JsonResponse
    {
        $this->sellerRepository
            ->whereIn('id', $request->input('indices'))
            ->update(['is_approved' => $request->input('value')]);

        $this->productRepository
            ->whereIn('marketplace_seller_id', $request->input('indices'))
            ->update(['is_approved' => $request->input('value')]);

        $sellers = $this->sellerRepository->findWhereIn('id', $request->input('indices'));

        foreach ($sellers as $seller) {
            try {
                Mail::to($seller->email)
                    ->send(new SellerApprovalNotification($seller));
            } catch (\Exception $e) {
            }
        }

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.sellers.index.update-success'),
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $sellerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($id)
    {
        $seller = $this->sellerRepository->findOrFail($id);

        $requiredFields = [
            'shop_title',
            'address1',
            'phone',
            'postcode',
            'city',
            'state',
            'country',
        ];

        foreach ($requiredFields as $field) {
            if (empty($seller->{$field})) {
                session()->flash('warning', trans('marketplace::app.admin.sellers.index.shop-validation', ['name' => $field]));

                return back();
            }
        }

        if (request()->input('query')) {
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
        } else {
            return view('marketplace::admin.sellers.products.search', compact('id'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $sellerId,  $productId
     * @return \Illuminate\View\View
     */
    public function assignProduct($sellerId, $productId)
    {
        $seller = $this->sellerRepository->findOrFail($sellerId);

        $baseProduct = $this->baseProductRepository->findOrFail($productId);

        $product = $this->productRepository->findOneWhere([
            'product_id'            => $productId,
            'marketplace_seller_id' => $sellerId,
        ]);

        if ($product) {
            session()->flash('error', trans('marketplace::app.admin.sellers.assign-product.already-selling'));

            return back();
        }

        if (! $this->sellerRepository->getAllowedProducts($seller)->has($baseProduct->type)) {
            session()->flash('error', trans('marketplace::app.admin.sellers.assign-product.product-not-allowed', ['type' => $baseProduct->type]));

            return back();
        }

        $inventorySources = core()->getCurrentChannel()->inventory_sources;

        return view('marketplace::admin.sellers.products.assign', compact('baseProduct', 'inventorySources', 'sellerId', 'productId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $sellerId,  $productId
     * @return \Illuminate\Http\Response
     */
    public function saveAssignProduct($sellerId, $productId)
    {
        $this->validate(request(), [
            'condition'   => 'required',
            'description' => 'required',
        ]);

        $seller = $this->sellerRepository->findOrFail($sellerId);

        if (! $this->sellerRepository->getAllowedProducts($seller)->has(request('product_type'))) {
            session()->flash('error', trans('marketplace::app.admin.sellers.assign-product.product-not-allowed', ['type' => request('product_type')]));

            return back();
        }

        $this->productRepository->createAssign(array_merge(request()->all(), [
            'product_id' => $productId,
            'is_owner'   => 0,
            'seller_id'  => $sellerId,
        ]));

        session()->flash('success', trans('marketplace::app.admin.sellers.assign-product.assign-successfully'));

        return redirect()->route('admin.marketplace.sellers.index');
    }
}
