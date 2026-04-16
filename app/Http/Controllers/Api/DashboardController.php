<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ringkasan Hari Ini
        $today = Carbon::today();
        $omzet_today = Order::whereDate('created_at', $today)->sum('total_harga');
        $orders_count = Order::whereDate('created_at', $today)->count();

        // 2. Stok Tipis (Kurang dari 5)
        $low_stock = Barang::where('stok', '<', 5)
            ->where('is_active', true)
            ->get(['nama_barang', 'stok', 'sku']);

        // 3. Tren Penjualan 7 Hari Terakhir (Untuk Line Chart)
        $sales_trend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $total = Order::whereDate('created_at', $date)->sum('total_harga');
            $sales_trend[] = [
                'label' => $date->format('d M'),
                'total' => (int) $total
            ];
        }

        // 4. Produk Terlaris (Top 5)
        $top_products = OrderDetail::select('nama_barang_backup', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('nama_barang_backup')
            ->orderBy('total_qty', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'today' => [
                    'omzet' => (int) $omzet_today,
                    'transactions' => $orders_count
                ],
                'low_stock' => $low_stock,
                'sales_trend' => $sales_trend,
                'top_products' => $top_products
            ]
        ]);
    }
}