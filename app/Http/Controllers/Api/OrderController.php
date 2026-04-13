<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('details')->latest()->get();
        return response()->json(['success' => true, 'data' => $orders]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bayar' => 'required|numeric',
            'metode_pembayaran' => 'required',
            'items' => 'required|array',
            'items.*.barang_id' => 'required|exists:barangs,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();
        try {
            // 1. Generate Nomor Invoice
            $invoice = 'INV-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));

            // 2. Hitung Total & Cek Stok
            $total_harga = 0;
            $order_details = [];

            foreach ($request->items as $item) {
                $barang = Barang::findOrFail($item['barang_id']);
                
                if ($barang->stok < $item['qty']) {
                    throw new \Exception("Stok barang {$barang->nama_barang} tidak mencukupi.");
                }

                $subtotal = $barang->harga_jual * $item['qty'];
                $total_harga += $subtotal;

                $order_details[] = [
                    'barang_id' => $barang->id,
                    'nama_barang_backup' => $barang->nama_barang,
                    'qty' => $item['qty'],
                    'harga_jual' => $barang->harga_jual,
                    'subtotal' => $subtotal
                ];

                // 3. Potong Stok
                $barang->decrement('stok', $item['qty']);
            }

            // 4. Simpan Order Utama
            $order = Order::create([
                'nomor_invoice' => $invoice,
                'total_harga' => $total_harga,
                'bayar' => $request->bayar,
                'kembali' => $request->bayar - $total_harga,
                'metode_pembayaran' => $request->metode_pembayaran,
                'catatan' => $request->catatan,
                'user_id' => $request->user_id ?? null,
            ]);

            // 5. Simpan Order Details
            foreach ($order_details as $detail) {
                $detail['order_id'] = $order->id;
                OrderDetail::create($detail);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil',
                'data' => $order->load('details')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function show(string $id)
    {
        $order = Order::with('details.barang')->find($id);
        if (!$order) return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        return response()->json(['success' => true, 'data' => $order]);
    }

    // API Khusus untuk Cetak Struk / Invoice (Akses via Nomor Invoice agar Aman)
    public function invoice(string $nomor_invoice)
    {
        $order = Order::with(['details', 'user'])->where('nomor_invoice', $nomor_invoice)->first();

        if (!$order) {
            return response()->json(['message' => 'Invoice tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data Invoice Berhasil Diambil',
            'data' => [
                'header' => [
                    'invoice_no' => $order->nomor_invoice,
                    'tanggal' => $order->created_at->format('d-m-Y H:i'),
                    'kasir' => $order->user->name ?? 'Kasir Utama',
                    'metode' => $order->metode_pembayaran
                ],
                'items' => $order->details->map(function ($item) {
                    return [
                        'nama' => $item->nama_barang_backup,
                        'harga' => (int) $item->harga_jual,
                        'qty' => $item->qty,
                        'subtotal' => (int) $item->subtotal
                    ];
                }),
                'summary' => [
                    'total' => (int) $order->total_harga,
                    'bayar' => (int) $order->bayar,
                    'kembali' => (int) $order->kembali,
                    'catatan' => $order->catatan
                ]
            ]
        ]);
    }
}
