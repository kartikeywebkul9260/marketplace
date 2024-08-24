<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Marketplace\Repositories\SellerFlagReasonRepository;
use Webkul\Marketplace\DataGrids\Admin\SellerFlagReasonDataGrid;

class SellerFlagReasonController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected SellerFlagReasonRepository $sellerFlagReasonRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(SellerFlagReasonDataGrid::class)->toJson();
        }

        return view('marketplace::admin.seller-flag-reasons.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'reason' => 'required',
        ]);

        $this->sellerFlagReasonRepository->create(request()->only([
            'reason',
            'status',
        ]));

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.seller-flag-reasons.index.create.success'),
        ]);
    }

    /**
     * Seller Flag Reason Details
     *
     * @param  int  $id
     */
    public function edit($id): JsonResponse
    {
        $flagReason = $this->sellerFlagReasonRepository->findOrFail($id);

        return new JsonResponse($flagReason);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     */
    public function update(): JsonResponse
    {
        $this->validate(request(), [
            'reason' => 'required',
        ]);

        $this->sellerFlagReasonRepository->update(request()->only([
            'reason',
            'status',
        ]), request()->id);

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.seller-flag-reasons.index.edit.success'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->sellerFlagReasonRepository->delete($id);

            return new JsonResponse([
                'message' => trans('marketplace::app.admin.seller-flag-reasons.index.delete-success'),
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('marketplace::app.admin.seller-flag-reasons.index.delete-failed'),
            ], 500);
        }
    }

    /**
     * Mass update the reviews.
     */
    public function massUpdate(MassUpdateRequest $request): JsonResponse
    {
        $this->sellerFlagReasonRepository
            ->whereIn('id', $request->input('indices'))
            ->update(['status' => $request->input('value')]);

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.seller-flag-reasons.index.update-success'),
        ], 200);
    }

    /**
     * Mass delete the flag reasons.
     */
    public function massDestroy(MassDestroyRequest $request): JsonResponse
    {
        try {
            $this->sellerFlagReasonRepository->whereIn('id', $request->input('indices'))->delete();

            return new JsonResponse([
                'message' => trans('marketplace::app.admin.seller-flag-reasons.index.delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('marketplace::app.admin.seller-flag-reasons.index.delete-failed'),
            ], 500);
        }
    }
}
