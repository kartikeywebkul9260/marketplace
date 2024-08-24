<?php

namespace Webkul\Marketplace\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\Repository;
use Webkul\Marketplace\Contracts\Seller;
use Webkul\Product\Repositories\ProductInventoryRepository;

class SellerRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @param  Webkul\Marketplace\Repositories\OrderItemRepository  $orderItemRepository
     * @param  Webkul\Product\Repositories\ProductInventoryRepository  $productInventoryRepository
     * @return void
     */
    public function __construct(
        protected ProductInventoryRepository $productInventoryRepository,
        protected OrderItemRepository $orderItemRepository,
        App $app
    ) {
        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model(): string
    {
        return Seller::class;
    }

    /**
     * Retrive seller from url
     *
     * @param  string  $url
     * @return mixed
     */
    public function findByUrlOrFail($url)
    {
        if ($seller = $this->findOneByField('url', $url)) {
            return $seller;
        }

        throw (new ModelNotFoundException)->setModel(
            get_class($this->model),
            $url
        );
    }

    /**
     * @param  string  $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = 'id')
    {
        Event::dispatch('marketplace.seller.profile.update.before', $id);

        $seller = $this->find($id);

        parent::update($data, $id);

        $this->uploadImages($data, $seller, 'logo');

        $this->uploadImages($data, $seller, 'banner');

        Event::dispatch('marketplace.seller.profile.update.after', $seller);

        return $seller;
    }

    /**
     * Upload seller images.
     *
     * @param  array  $data
     * @param  object  $seller
     * @param  string  $type
     * @return void
     */
    public function uploadImages($data, $seller, $type)
    {
        if (isset($data[$type])) {
            $request = request();

            foreach ($data[$type] as $imageId => $image) {
                $file = $type.'.'.$imageId;
                $dir = 'seller/'.$seller->id;

                if ($request->hasFile($file)) {
                    if ($seller->{$type}) {
                        Storage::delete($seller->{$type});
                    }

                    $seller->{$type} = $request->file($file)->store($dir);
                    $seller->save();
                }
            }
        } else {
            if ($seller->{$type}) {
                Storage::delete($seller->{$type});
            }

            $seller->{$type} = null;
            $seller->save();
        }
    }

    /**
     * Returns top six popular sellers
     *
     * @return Collection
     */
    public function getPopularSellers()
    {
        return $this->getModel()
            ->leftJoin('marketplace_orders', 'marketplace_sellers.id', 'marketplace_orders.marketplace_seller_id')
            ->leftJoin('marketplace_seller_reviews', function ($join) {
                $join->on('marketplace_seller_reviews.marketplace_seller_id', 'marketplace_sellers.id')
                    ->where('marketplace_seller_reviews.status', 'approved');
            })
            ->select('marketplace_sellers.*')
            ->addSelect(
                DB::raw('SUM(total_qty_ordered) as total_qty_ordered'),
                DB::raw('AVG(rating) as avg_rating'),
                DB::raw('COUNT(DISTINCT marketplace_seller_reviews.id) as total_rating')
            )
            ->groupBy('marketplace_sellers.id')
            ->where('marketplace_sellers.shop_title', '<>', null)
            ->where('marketplace_sellers.is_approved', 1)
            ->orderBy('total_qty_ordered', 'DESC')
            ->limit(6)
            ->get();
    }

    /**
     * @return mixed
     */
    public function deleteInventory($id)
    {
        $inventories = $this->productInventoryRepository->findWhere([
            'vendor_id' => $id,
        ]);

        if (count($inventories)) {
            foreach ($inventories as $inventory) {
                if (isset($inventory)) {
                    $this->productInventoryRepository->delete($inventory->id);
                }
            }
        }
    }

    /**
     * Returns seller allowed Product Types
     *
     * @return object
     */
    public function getAllowedProducts($seller = null)
    {
        $seller = $seller ?? auth()->guard('seller')->user();

        return collect(config('product_types'))->only($seller->allowed_product_types);
    }
}
