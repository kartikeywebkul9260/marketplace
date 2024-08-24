<?php

namespace Webkul\Marketplace\Helpers\Reporting;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\Helpers\Reporting\AbstractReporting;
use Webkul\Marketplace\Repositories\InvoiceRepository;
use Webkul\Marketplace\Repositories\OrderItemRepository;
use Webkul\Marketplace\Repositories\OrderRepository;
use Webkul\Marketplace\Repositories\RefundRepository;
use Webkul\Marketplace\Repositories\TransactionRepository;

class Sale extends AbstractReporting
{
    /**
     * Create a helper instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected OrderItemRepository $orderItemRepository,
        protected InvoiceRepository $invoiceRepository,
        protected RefundRepository $refundRepository,
        protected TransactionRepository $transactionRepository,
    ) {
        parent::__construct();
    }

    /**
     * Retrieves total orders and their progress.
     *
     * @param  object  $seller
     * @return array
     */
    public function getTotalOrdersProgress($seller)
    {
        return [
            'previous' => $previous = $this->getTotalOrders($seller, $this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->getTotalOrders($seller, $this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Returns previous orders over time
     *
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getPreviousTotalOrdersOverTime($period = 'auto', $includeEmpty = true): array
    {
        return $this->getTotalOrdersOverTime($this->lastStartDate, $this->lastEndDate, $period, $includeEmpty);
    }

    /**
     * Returns current orders over time
     *
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getCurrentTotalOrdersOverTime($period = 'auto', $includeEmpty = true): array
    {
        return $this->getTotalOrdersOverTime($this->startDate, $this->endDate, $period, $includeEmpty);
    }

    /**
     * Retrieves total orders
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalOrders($seller, $startDate, $endDate): int
    {
        return $this->orderRepository
            ->where('marketplace_seller_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    /**
     * Returns orders over time
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getTotalOrdersOverTime($startDate, $endDate, $period, $includeEmpty): array
    {
        return $this->getOverTimeStats(
            $startDate,
            $endDate,
            'COUNT(*)',
            $period
        );
    }

    /**
     * Retrieves today orders and their progress.
     *
     * @param  object  $seller
     */
    public function getTodayOrdersProgress($seller): array
    {
        return [
            'previous' => $previous = $this->getTotalOrders($seller, now()->subDay()->startOfDay(), now()->subDay()->endOfDay()),
            'current'  => $current = $this->getTotalOrders($seller, now()->today(), now()->endOfDay()),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves orders
     *
     * @return array
     */
    public function getTodayOrders()
    {
        return $this->orderRepository
            ->with(['addresses', 'payment', 'items'])
            ->whereBetween('orders.created_at', [now()->today(), now()->endOfDay()])
            ->get();
    }

    /**
     * Retrieves total sales and their progress.
     *
     * @param  object  $seller
     */
    public function getTotalSalesProgress($seller): array
    {
        return [
            'previous'        => $previous = $this->getTotalSales($seller, $this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getTotalSales($seller, $this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves today sales and their progress.
     *
     * @param  object  $seller
     */
    public function getTodaySalesProgress($seller): array
    {
        return [
            'previous' => $previous = $this->getTotalSales($seller, now()->subDay()->startOfDay(), now()->subDay()->endOfDay()),
            'current'  => $current = $this->getTotalSales($seller, now()->today(), now()->endOfDay()),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total sales
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalSales($seller, $startDate, $endDate): float
    {
        return $this->orderRepository
            ->where('marketplace_seller_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('base_sub_total_invoiced - base_sub_total_refunded'));
    }

    /**
     * Returns previous sales over time
     *
     * @param  object  $seller
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getPreviousTotalSalesOverTime($seller, $period = 'auto', $includeEmpty = true): array
    {
        return $this->getTotalSalesOverTime($seller, $this->lastStartDate, $this->lastEndDate, $period, $includeEmpty);
    }

    /**
     * Returns current sales over time
     *
     * @param  object  $seller
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getCurrentTotalSalesOverTime($seller, $period = 'auto', $includeEmpty = true): array
    {
        return $this->getTotalSalesOverTime($seller, $this->startDate, $this->endDate, $period, $includeEmpty);
    }

    /**
     * Returns sales over time
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getTotalSalesOverTime($seller, $startDate, $endDate, $period, $includeEmpty): array
    {
        return $this->getOverTimeStats(
            $seller,
            $startDate,
            $endDate,
            'SUM(base_grand_total_invoiced - base_grand_total_refunded)',
            $period
        );
    }

    /**
     * Retrieves average sales and their progress.
     *
     * @param  object  $seller
     */
    public function getAverageSalesProgress($seller): array
    {
        return [
            'previous'        => $previous = $this->getAverageSales($seller, $this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getAverageSales($seller, $this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves average sales
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getAverageSales($seller, $startDate, $endDate): ?float
    {
        return $this->orderRepository
            ->where('marketplace_seller_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->avg(DB::raw('base_sub_total_invoiced - base_sub_total_refunded'));
    }

    /**
     * Retrieves seller total payout progress.
     *
     * @param  object  $seller
     */
    public function getTotalPayoutProgress($seller): array
    {
        return [
            'previous'        => $previous = $this->getTotalPayout($seller, $this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getTotalPayout($seller, $this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'percent'         => $this->getAverageTotalPayout($seller, $this->startDate, $this->endDate),
        ];
    }

    /**
     * Retrieves seller remaining payout progress.
     *
     * @param  object  $seller
     */
    public function getRemainingPayoutProgress($seller): array
    {
        return [
            'previous'        => $previous = $this->getRemainingPayout($seller, $this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getRemainingPayout($seller, $this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'percent'         => $this->getAverageRemainingPayout($seller, $this->startDate, $this->endDate),
        ];
    }

    /**
     * Retrieves seller remaining payout.
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalPayout($seller, $startDate, $endDate): ?float
    {
        return $this->transactionRepository
            ->where('marketplace_seller_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('base_total'));
    }

    /**
     * Retrieves seller remaining payout.
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getRemainingPayout($seller, $startDate, $endDate): ?float
    {
        return $this->orderRepository
            ->where('marketplace_seller_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('seller_payout_status', ['pending', 'requested'])
            ->where('status', 'completed')
            ->sum(DB::raw('base_seller_total'));
    }

    /**
     * Retrieves average total payout.
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getAverageTotalPayout($seller, $startDate, $endDate): ?float
    {
        if (
            ! $this->getRemainingPayout($seller, $startDate, $endDate)
            || ! $this->getTotalPayout($seller, $startDate, $endDate)
        ) {
            return 0;
        }

        return $this->getTotalPayout($seller, $startDate, $endDate) * 100 /
        ($this->getTotalPayout($seller, $startDate, $endDate) + $this->getRemainingPayout($seller, $startDate, $endDate));
    }

    /**
     * Retrieves average remaining payout.
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getAverageRemainingPayout($seller, $startDate, $endDate): ?float
    {
        if (
            ! $this->getRemainingPayout($seller, $startDate, $endDate)
            || ! $this->getTotalPayout($seller, $startDate, $endDate)
        ) {
            return 0;
        }

        return $this->getRemainingPayout($seller, $startDate, $endDate) * 100 /
        ($this->getTotalPayout($seller, $startDate, $endDate) + $this->getRemainingPayout($seller, $startDate, $endDate));
    }

    /**
     * Returns previous average sales over time
     *
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getPreviousAverageSalesOverTime($period = 'auto', $includeEmpty = true): array
    {
        return $this->getAverageSalesOverTime($this->lastStartDate, $this->lastEndDate, $period, $includeEmpty);
    }

    /**
     * Returns current average sales over time
     *
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getCurrentAverageSalesOverTime($period = 'auto', $includeEmpty = true): array
    {
        return $this->getAverageSalesOverTime($this->startDate, $this->endDate, $period, $includeEmpty);
    }

    /**
     * Returns average sales over time
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getAverageSalesOverTime($startDate, $endDate, $period, $includeEmpty): array
    {
        return $this->getOverTimeStats(
            $startDate,
            $endDate,
            'AVG(base_grand_total_invoiced - base_grand_total_refunded)',
            $period
        );
    }

    /**
     * Retrieves refunds and their progress.
     */
    public function getRefundsProgress(): array
    {
        return [
            'previous'        => $previous = $this->getRefunds($this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getRefunds($this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves refunds
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getRefunds($startDate, $endDate): float
    {
        return $this->orderRepository
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('base_grand_total_refunded'));
    }

    /**
     * Returns previous refunds over time
     *
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getPreviousRefundsOverTime($period = 'auto', $includeEmpty = true): array
    {
        return $this->getRefundsOverTime($this->lastStartDate, $this->lastEndDate, $period, $includeEmpty);
    }

    /**
     * Returns current refunds over time
     *
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getCurrentRefundsOverTime($period = 'auto', $includeEmpty = true): array
    {
        return $this->getRefundsOverTime($this->startDate, $this->endDate, $period, $includeEmpty);
    }

    /**
     * Returns refunds over time
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getRefundsOverTime($startDate, $endDate, $period, $includeEmpty): array
    {
        return $this->getOverTimeStats(
            $startDate,
            $endDate,
            'SUM(base_grand_total_refunded)',
            $period
        );
    }

    /**
     * Retrieves tax collected and their progress.
     */
    public function getTaxCollectedProgress(): array
    {
        return [
            'previous'        => $previous = $this->getTaxCollected($this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getTaxCollected($this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves tax collected
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getTaxCollected($startDate, $endDate): float
    {
        return $this->orderRepository
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('base_tax_amount_invoiced - base_tax_amount_refunded'));
    }

    /**
     * Returns previous tax collected over time
     *
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getPreviousTaxCollectedOverTime($period = 'auto', $includeEmpty = true): array
    {
        return $this->getTaxCollectedOverTime($this->lastStartDate, $this->lastEndDate, $period, $includeEmpty);
    }

    /**
     * Returns current tax collected over time
     *
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getCurrentTaxCollectedOverTime($period = 'auto', $includeEmpty = true): array
    {
        return $this->getTaxCollectedOverTime($this->startDate, $this->endDate, $period, $includeEmpty);
    }

    /**
     * Returns tax collected over time
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getTaxCollectedOverTime($startDate, $endDate, $period, $includeEmpty): array
    {
        return $this->getOverTimeStats(
            $startDate,
            $endDate,
            'SUM(base_tax_amount_invoiced - base_tax_amount_refunded)',
            $period
        );
    }

    /**
     * Returns top tax categories
     *
     * @param  int  $limit
     */
    public function getTopTaxCategories($limit = null): Collection
    {
        return $this->orderItemRepository
            ->leftJoin('tax_categories', 'order_items.tax_category_id', '=', 'tax_categories.id')
            ->select('tax_categories.id as tax_category_id', 'tax_categories.name')
            ->addSelect(DB::raw('SUM(base_tax_amount_invoiced - base_tax_amount_refunded) as total'))
            ->whereBetween('order_items.created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('tax_category_id')
            ->groupBy('tax_category_id')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    /**
     * Retrieves shipping collected and their progress.
     */
    public function getShippingCollectedProgress(): array
    {
        return [
            'previous'        => $previous = $this->getShippingCollected($this->lastStartDate, $this->lastEndDate),
            'current'         => $current = $this->getShippingCollected($this->startDate, $this->endDate),
            'formatted_total' => core()->formatBasePrice($current),
            'progress'        => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves shipping collected
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getShippingCollected($startDate, $endDate): float
    {
        return $this->orderRepository
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum(DB::raw('base_shipping_invoiced - base_shipping_refunded'));
    }

    /**
     * Returns previous shipping collected over time
     *
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getPreviousShippingCollectedOverTime($period = 'auto', $includeEmpty = true): array
    {
        return $this->getShippingCollectedOverTime($this->lastStartDate, $this->lastEndDate, $period, $includeEmpty);
    }

    /**
     * Returns current shipping collected over time
     *
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getCurrentShippingCollectedOverTime($period = 'auto', $includeEmpty = true): array
    {
        return $this->getShippingCollectedOverTime($this->startDate, $this->endDate, $period, $includeEmpty);
    }

    /**
     * Returns shipping collected over time
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getShippingCollectedOverTime($startDate, $endDate, $period, $includeEmpty): array
    {
        return $this->getOverTimeStats(
            $startDate,
            $endDate,
            'SUM(base_shipping_invoiced - base_shipping_refunded)',
            $period
        );
    }

    /**
     * Returns top shipping methods
     *
     * @param  int  $limit
     */
    public function getTopShippingMethods($limit = null): Collection
    {
        return $this->orderRepository
            ->select('shipping_title as title')
            ->addSelect(DB::raw('SUM(base_shipping_invoiced - base_shipping_refunded) as total'))
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('shipping_method')
            ->groupBy('shipping_method')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    /**
     * Returns top payment methods
     *
     * @param  int  $limit
     */
    public function getTopPaymentMethods($limit = null): Collection
    {
        return $this->orderRepository
            ->leftJoin('order_payment', 'orders.id', '=', 'order_payment.order_id')
            ->select('method', 'method_title as title')
            ->addSelect(DB::raw('COUNT(*) as total'))
            ->addSelect(DB::raw('SUM(base_grand_total) as base_total'))
            ->whereBetween('orders.created_at', [$this->startDate, $this->endDate])
            ->groupBy('method')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    /**
     * Retrieves total unique cart users
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @return array
     */
    public function getTotalUniqueOrdersUsers($startDate, $endDate): int
    {
        return $this->orderRepository
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('CONCAT(customer_email, "-", customer_id)'))
            ->get()
            ->count();
    }

    /**
     * Returns over time stats.
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $valueColumn
     * @param  string  $period
     */
    public function getOverTimeStats($seller, $startDate, $endDate, $valueColumn, $period = 'auto'): array
    {
        $config = $this->getTimeInterval($startDate, $endDate, $period);

        $groupColumn = $config['group_column'];

        $results = $this->orderRepository
            ->select(
                DB::raw("$groupColumn AS date"),
                DB::raw("$valueColumn AS total"),
                DB::raw('COUNT(*) AS count')
            )
            ->where('marketplace_seller_id', $seller->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get();

        foreach ($config['intervals'] as $interval) {
            $total = $results->where('date', $interval['filter'])->first();

            $stats[] = [
                'label' => $interval['start'],
                'total' => $total?->total ?? 0,
                'count' => $total?->count ?? 0,
            ];
        }

        return $stats;
    }
}
