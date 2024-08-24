<?php

namespace Webkul\Marketplace\Http\Controllers\Shop;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Webkul\Marketplace\Repositories\ProductDownloadableLinkRepository;
use Webkul\Marketplace\Repositories\ProductDownloadableSampleRepository;
use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Product\Repositories\ProductRepository as BaseProductRepository;
use Webkul\Shop\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected ProductRepository $productRepository,
        protected BaseProductRepository $baseProductRepository,
        protected ProductDownloadableSampleRepository $productDownloadableSampleRepository,
        protected ProductDownloadableLinkRepository $productDownloadableLinkRepository
    ) {
    }

    /**
     * Method to populate the seller product page which will be populated.
     *
     * @param  string  $url
     */
    public function index($url): JsonResource
    {
        $seller = $this->sellerRepository->findByUrlOrFail($url);

        return ProductResource::collection($this->productRepository->getAll($seller));
    }

    /**
     * Product offers by sellers
     *
     * @param  int  $id
     * @return mixed
     */
    public function offers($id)
    {
        $product = $this->baseProductRepository->findOrFail($id);

        if ($product->type == 'configurable') {
            session()->flash('error', trans('shop::app.checkout.cart.integrity.missing_options'));

            return redirect()->route('shop.product_or_category.index', ['slug' => $product->url_key]);
        }

        $sellerCount = $this->productRepository->getSellerCount($product);

        if (! $sellerCount) {
            return redirect()->route('shop.home.index');
        }

        return view('marketplace::shop.products.offers')
            ->with([
                'product'          => $product,
                'sellerCount'      => $sellerCount,
            ]);
    }

    /**
     * Download the for the specified resource.
     *
     * @return \Illuminate\Http\Response|\Exception
     */
    public function downloadSample()
    {
        try {
            if (request('type') == 'link') {
                $productDownloadableLink = $this->productDownloadableLinkRepository->findOrFail(request('id'));

                if ($productDownloadableLink->sample_type == 'file') {
                    $privateDisk = Storage::disk('private');

                    return $privateDisk->exists($productDownloadableLink->sample_file)
                        ? $privateDisk->download($productDownloadableLink->sample_file)
                        : abort(404);
                }

                $fileName = substr($productDownloadableLink->sample_url, strrpos($productDownloadableLink->sample_url, '/') + 1);

                $tempImage = tempnam(sys_get_temp_dir(), $fileName);

                copy($productDownloadableLink->sample_url, $tempImage);

                return response()->download($tempImage, $fileName);
            }

            $productDownloadableSample = $this->productDownloadableSampleRepository->findOrFail(request('id'));

            if ($productDownloadableSample->type == 'file') {
                return Storage::download($productDownloadableSample->file);
            }

            $fileName = $name = substr($productDownloadableSample->url, strrpos($productDownloadableSample->url, '/') + 1);

            $tempImage = tempnam(sys_get_temp_dir(), $fileName);

            copy($productDownloadableSample->url, $tempImage);

            return response()->download($tempImage, $fileName);
        } catch (\Exception $e) {
            abort(404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $sellerId
     * @return mixed
     */
    public function topSellingProducts($sellerId): JsonResource
    {
        $productIds = $this->productRepository->getTopSellingProducts($sellerId);

        return ProductResource::collection($this->baseProductRepository->findWhereIn('id', $productIds));
    }
}
