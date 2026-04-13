<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Barang;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Transaksi 1
        $kopi = Barang::where('sku', 'MNM-001')->first();
        $teh = Barang::where('sku', 'MNM-002')->first();

        $order1 = Order::create([
            'nomor_invoice' => 'INV-20260413-SAMPLE01',
            'total_harga' => 23000, // (18000 + 5000)
            'bayar' => 50000,
            'kembali' => 27000,
            'metode_pembayaran' => 'Tunai',
            'catatan' => 'Es teh jangan terlalu manis',
        ]);

        OrderDetail::create([
            'order_id' => $order1->id,
            'barang_id' => $kopi->id,
            'nama_barang_backup' => $kopi->nama_barang,
            'qty' => 1,
            'harga_jual' => 18000,
            'subtotal' => 18000,
        ]);

        OrderDetail::create([
            'order_id' => $order1->id,
            'barang_id' => $teh->id,
            'nama_barang_backup' => $teh->nama_barang,
            'qty' => 1,
            'harga_jual' => 5000,
            'subtotal' => 5000,
        ]);

        // Transaksi 2 (QRIS)
        $nasi = Barang::where('sku', 'MKN-001')->first();

        $order2 = Order::create([
            'nomor_invoice' => 'INV-20260413-SAMPLE02',
            'total_harga' => 50000, // (25000 x 2)
            'bayar' => 50000,
            'kembali' => 0,
            'metode_pembayaran' => 'QRIS',
        ]);

        OrderDetail::create([
            'order_id' => $order2->id,
            'barang_id' => $nasi->id,
            'nama_barang_backup' => $nasi->nama_barang,
            'qty' => 2,
            'harga_jual' => 25000,
            'subtotal' => 50000,
        ]);
    }
}
