<?php
namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Hutang;
use App\Models\Piutang;
use App\Models\SalesOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Carbon::setLocale('id');

        // $startOfMonth = Carbon::now()->startOfMonth();
        // $endOfMonth   = Carbon::now()->endOfMonth();

        // $soPending = SalesOrder::where('status', 1)
        //     ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
        //     ->count();

        // $soDisetujui = SalesOrder::where('status', 2)
        //     ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
        //     ->count();
        // $soKonfirmasi = Invoice::where('status', 2)->count();

        // $bulanSekarang = Carbon::now()->translatedFormat('F Y');

        // $segmentEcommerce = SalesOrder::where('id_segment', 1)
        //     ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
        //     ->count();

        // $segmentGrosir = SalesOrder::where('id_segment', 2)
        //     ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
        //     ->count();

        // $segmentSemiGrosir = SalesOrder::where('id_segment', 4)
        //     ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
        //     ->count();

        // $segmentOffline = SalesOrder::where('id_segment', 5)
        //     ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
        //     ->count();

        // $jenisPenjualanEcommerce = SalesOrder::with('jenis_penjualan')
        //     ->where('id_segment', 1)
        //     ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
        //     ->get()
        //     ->groupBy('id_jenis_penjualan')
        //     ->map(function ($group) {
        //         return [
        //             'nama'   => optional($group->first()->jenis_penjualan)->nama ?? 'Tidak diketahui',
        //             'jumlah' => $group->count(),
        //         ];
        //     })->values();

        // $monthList = [
        //     1  => 'Januari',
        //     2  => 'Februari',
        //     3  => 'Maret',
        //     4  => 'April',
        //     5  => 'Mei',
        //     6  => 'Juni',
        //     7  => 'Juli',
        //     8  => 'Agustus',
        //     9  => 'September',
        //     10 => 'Oktober',
        //     11 => 'November',
        //     12 => 'Desember',
        // ];

        return view('admin.dashboard.dashboard');
    }

    public function filter(Request $request)
    {
        $tahun = $request->tahun;
        $bulan = $request->bulan;

        $monthList = [
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        // ================== TOTAL PIUTANG ==================
        $piutangQuery = Piutang::whereYear('tanggal_piutang', $tahun);
        if ($bulan != 0) {
            $piutangQuery->whereMonth('tanggal_piutang', $bulan);
        }
        $totalPiutang = $piutangQuery->sum('sisa_bayar');

        // ================== TOTAL HUTANG ==================
        $hutangQuery = Hutang::whereYear('tanggal_hutang', $tahun);
        if ($bulan != 0) {
            $hutangQuery->whereMonth('tanggal_hutang', $bulan);
        }
        $totalHutang = $hutangQuery->sum('sisa_bayar');

        // ================== NILAI BARANG ==================
        $nilaiBarang = Barang::all()->sum(fn($barang) => $barang->hpp * ($barang->stok_gudang + $barang->stok_toko));

        // ================== TOTAL TRANSAKSI ==================
        $soQuery = SalesOrder::whereYear('tanggal', $tahun);
        if ($bulan != 0) {
            $soQuery->whereMonth('tanggal', $bulan);
        }
        $totalTransaksi = $soQuery->sum('grand_total');

        $soPending   = (clone $soQuery)->where('status', 1)->count();
        $soDisetujui = (clone $soQuery)->where('status', 2)->count();
        $soDitolak   = (clone $soQuery)->where('status', 3)->count();

        $segmentEcommerce  = (clone $soQuery)->where('id_segment', 1)->count();
        $segmentGrosir     = (clone $soQuery)->where('id_segment', 2)->count();
        $segmentSemiGrosir = (clone $soQuery)->where('id_segment', 4)->count();
        $segmentOffline    = (clone $soQuery)->where('id_segment', 5)->count();

        return response()->json([
            'totalPiutang'      => number_format($totalPiutang, 0, ',', '.'),
            'totalHutang'       => number_format($totalHutang, 0, ',', '.'),
            'nilaiBarang'       => number_format($nilaiBarang, 0, ',', '.'),
            'totalTransaksi'    => number_format($totalTransaksi, 0, ',', '.'),
            'soPending'         => number_format($soPending, 0, ',', '.'),
            'soDisetujui'       => number_format($soDisetujui, 0, ',', '.'),
            'soDitolak'         => number_format($soDitolak, 0, ',', '.'),
            'segmentEcommerce'  => number_format($segmentEcommerce, 0, ',', '.'),
            'segmentGrosir'     => number_format($segmentGrosir, 0, ',', '.'),
            'segmentSemiGrosir' => number_format($segmentSemiGrosir, 0, ',', '.'),
            'segmentOffline'    => number_format($segmentOffline, 0, ',', '.'),

            // Untuk heading
            'bulan'             => $bulan,
            'bulanNama'         => $bulan != 0 ? $monthList[$bulan] : null,
            'tahun'             => $tahun,
        ]);
    }

}
