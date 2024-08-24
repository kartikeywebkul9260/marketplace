<?php

namespace Webkul\Marketplace\Helpers\Reporting;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\Helpers\Reporting\AbstractReporting;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\Marketplace\Repositories\OrderRepository;

class Customer extends AbstractReporting
{
    /**
     * Create a helper instance.
     *
     * @return void
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected OrderRepository $orderRepository,
    ) {
        parent::__construct();
    }

    /**
     * Retrieves total customers and their progress.
     *
     * @param  object  $seller
     */
    public function getTotalCustomersProgress($seller): array
    {
        return [
            'previous' => $previous = $this->getTotalCustomers($seller, $this->lastStartDate, $this->lastEndDate),
            'current'  => $current = $this->getTotalCustomers($seller, $this->startDate, $this->endDate),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Returns previous customers over time
     *
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getPreviousTotalCustomersOverTime($period = 'auto', $includeEmpty = true): array
    {
        return $this->getTotalCustomersOverTime($this->lastStartDate, $this->lastEndDate, $period);
    }

    /**
     * Returns current customers over time
     *
     * @param  string  $period
     * @param  bool  $includeEmpty
     */
    public function getCurrentTotalCustomersOverTime($period = 'auto', $includeEmpty = true): array
    {
        return $this->getTotalCustomersOverTime($this->startDate, $this->endDate, $period);
    }

    /**
     * Retrieves today customers and their progress.
     *
     * @param  object  $seller
     */
    public function getTodayCustomersProgress($seller): array
    {
        return [
            'previous' => $previous = $this->getTotalCustomers($seller, now()->subDay()->startOfDay(), now()->subDay()->endOfDay()),
            'current'  => $current = $this->getTotalCustomers($seller, now()->today(), now()->endOfDay()),
            'progress' => $this->getPercentageChange($previous, $current),
        ];
    }

    /**
     * Retrieves total customers by date
     *
     * @param  object  $seller
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     */
    public function getTotalCustomers($seller, $startDate, $endDate): int
    {
        return $this->orderRepository
            ->leftJoin('orders', 'orders.id', 'marketplace_orders.order_id')
            ->leftJoin('customers', 'customers.id', 'orders.customer_id')
            ->where('marketplace_orders.marketplace_seller_id', $seller->id)
            ->whereBetween('marketplace_orders.created_at', [$startDate, $endDate])
            ->distinct('orders.customer_id')
            ->count();
    }

    /**
     * Gets customer with most sales.
     *
     * @param  object  $seller
     * @param  int  $limit
     */
    public function getCustomersWithMostSales($seller, $limit): Collection
    {
        $tablePrefix = DB::getTablePrefix();

        return $this->orderRepository
            ->leftJoin('orders', 'marketplace_orders.order_id', '=', 'orders.id')
            ->leftJoin('customers', 'customers.id', 'orders.customer_id')
            ->leftJoin('customer_groups', 'customer_groups.id', 'customers.customer_group_id')
            ->addSelect(
                'orders.customer_id as id',
                'orders.customer_email as email',
                DB::raw('CONCAT('.$tablePrefix.'orders.customer_first_name, " ", '.$tablePrefix.'orders.customer_last_name) as full_name'),
                'customer_groups.name as group_name',
                DB::raw('SUM(orders.base_grand_total_invoiced - orders.base_grand_total_refunded) as total'),
                DB::raw('COUNT(*) as orders')
            )
            ->where('marketplace_orders.marketplace_seller_id', $seller->id)
            ->whereBetween('orders.created_at', [$this->startDate, $this->endDate])
            ->groupBy(DB::raw('CONCAT(customer_email, "-", customer_id)'))
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    /**
     * Gets customer with most orders.
     *
     * @param  int  $limit
     */
    public function getCustomersWithMostOrders($limit = null): Collection
    {
        $tablePrefix = DB::getTablePrefix();

        return $this->orderRepository
            ->addSelect(
                'orders.customer_id as id',
                'orders.customer_email as email',
                DB::raw('CONCAT('.$tablePrefix.'orders.customer_first_name, " ", '.$tablePrefix.'orders.customer_last_name) as full_name'),
                DB::raw('COUNT(*) as orders')
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->groupBy(DB::raw('CONCAT(customer_email, "-", customer_id)'))
            ->orderByDesc('orders')
            ->limit($limit)
            ->get();
    }

    /**
     * Returns over time stats.
     *
     * @param  \Carbon\Carbon  $startDate
     * @param  \Carbon\Carbon  $endDate
     * @param  string  $period
     */
    public function getTotalCustomersOverTime($startDate, $endDate, $period = 'auto'): array
    {
        $config = $this->getTimeInterval($startDate, $endDate, $period);

        $groupColumn = $config['group_column'];

        $results = $this->customerRepository
            ->select(
                DB::raw("$groupColumn AS date"),
                DB::raw('COUNT(*) AS total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get();

        $stats = [];

        foreach ($config['intervals'] as $interval) {
            $total = $results->where('date', $interval['filter'])->first();

            $stats[] = [
                'label' => $interval['start'],
                'total' => $total?->total ?? 0,
            ];
        }

        return $stats;
    }
}
