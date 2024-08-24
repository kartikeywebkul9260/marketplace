<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\JsonResponse;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Marketplace\DataGrids\Admin\SellerCategoryDataGrid;
use Webkul\Marketplace\Repositories\SellerCategoryRepository;
use Webkul\Marketplace\Repositories\SellerRepository;

class SellerCategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected CategoryRepository $categoryRepository,
        protected SellerCategoryRepository $sellerCategoryRepository
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
            return app(SellerCategoryDataGrid::class)->toJson();
        }

        return view('marketplace::admin.seller-categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('marketplace::admin.seller-categories.create')
            ->with([
                'sellers'    => $this->sellerRepository->findByField('is_approved', 1),
                'categories' => $this->categoryRepository->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id),
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
            'seller_id'  => 'required',
            'categories' => 'required',
        ]);

        $this->sellerCategoryRepository->updateOrCreate(
            ['seller_id'  => request()->input('seller_id')],
            ['categories' => request()->input('categories')],
        );

        session()->flash('success', trans('marketplace::app.admin.seller-categories.index.create-success'));

        return redirect()->route('admin.marketplace.seller_categories.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('marketplace::admin.seller-categories.edit')
            ->with([
                'sellerCategory' => $this->sellerCategoryRepository->findOrFail($id),
                'categories'     => $this->categoryRepository->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id),
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        if (! request()->has('categories')) {
            request()->merge(['categories' => []]);
        }

        $this->sellerCategoryRepository->update(request()->only(['categories']), $id);

        session()->flash('success', trans('marketplace::app.admin.seller-categories.index.update-success'));

        return redirect()->route('admin.marketplace.seller_categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->sellerCategoryRepository->delete($id);

            return new JsonResponse([
                'message' => trans('marketplace::app.admin.seller-categories.index.delete-success'),
            ], 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => trans('marketplace::app.admin.categories.index.delete-failed'),
            ], 500);
        }
    }

    /**
     * Mass delete the flag reasons.
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        try {
            $this->sellerCategoryRepository->whereIn('id', $massDestroyRequest->input('indices'))->delete();

            return new JsonResponse([
                'message' => trans('marketplace::app.admin.seller-categories.index.delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
