<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;

use App\Classes\APIResponse;

class LaporanController extends Controller
{
    var $response;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->response = new APIResponse;
    }

    public function spareparts_terlaris(Request $request)
    {
        $this->response->data = DB::select('
            SELECT A.bulan, nama_barang, tipe_barang, jumlah_penjualan
            FROM (SELECT 1 AS bulan
                  UNION SELECT 2 AS bulan
                  UNION SELECT 3 AS bulan
                  UNION SELECT 4 AS bulan
                  UNION SELECT 5 AS bulan
                  UNION SELECT 6 AS bulan
                  UNION SELECT 7 AS bulan
                  UNION SELECT 8 AS bulan
                  UNION SELECT 9 AS bulan
                  UNION SELECT 10 AS bulan
                  UNION SELECT 11 AS bulan
                  UNION SELECT 12 AS bulan
                 ) AS A
            LEFT JOIN (SELECT bulan, MIN(nama_barang) AS nama_barang, MIN(tipe_barang) AS tipe_barang, MAX(jumlah_penjualan) as jumlah_penjualan
                       FROM (SELECT MONTH(p.tgl_transaksi) AS bulan, s.nama AS nama_barang, s.tipe AS tipe_barang, SUM(dps.jumlah) AS jumlah_penjualan
                             FROM penjualan p
                             JOIN detail_penjualan dp ON (dp.id_penjualan = p.id)
                             JOIN detail_penjualan_spareparts dps ON (dps.id_detail_penjualan = dp.id)
                             JOIN spareparts s ON (s.kode = dps.kode_spareparts)
                             WHERE YEAR(p.tgl_transaksi) = ? AND p.status = 3
                             GROUP BY bulan, nama_barang, tipe_barang
                             ORDER BY bulan, nama_barang, jumlah_penjualan DESC
                            ) T
                       GROUP BY bulan
                      ) AS B ON B.bulan = A.bulan
            ORDER BY A.bulan
        ', [$request->tahun]);

        return $this->response->make();
    }

    public function pendapatan_bulanan(Request $request)
    {
        $this->response->data = DB::select('
            SELECT A.bulan, jasa_service, spareparts, total
            FROM (SELECT 1 AS bulan
                  UNION SELECT 2 AS bulan
                  UNION SELECT 3 AS bulan
                  UNION SELECT 4 AS bulan
                  UNION SELECT 5 AS bulan
                  UNION SELECT 6 AS bulan
                  UNION SELECT 7 AS bulan
                  UNION SELECT 8 AS bulan
                  UNION SELECT 9 AS bulan
                  UNION SELECT 10 AS bulan
                  UNION SELECT 11 AS bulan
                  UNION SELECT 12 AS bulan
                 ) AS A
            LEFT JOIN (SELECT bulan, MIN(jasa_service) AS jasa_service, MIN(spareparts) AS spareparts, MIN(jasa_service) + MIN(spareparts) AS total
                       FROM (SELECT MONTH(p.tgl_transaksi) AS bulan, SUM(dps.jumlah*dps.harga) AS spareparts, null AS jasa_service
                             FROM penjualan p
                             JOIN detail_penjualan dp ON (dp.id_penjualan = p.id)
                             JOIN detail_penjualan_spareparts dps ON (dps.id_detail_penjualan = dp.id)
                             WHERE YEAR(p.tgl_transaksi) = ? AND p.status = 3
                             GROUP BY bulan
                             UNION
                             SELECT MONTH(p.tgl_transaksi) AS bulan, null AS spareparts, SUM(dpjs.jumlah*dpjs.harga) AS jasa_service
                             FROM penjualan p
                             JOIN detail_penjualan dp ON (dp.id_penjualan = p.id)
                             JOIN detail_penjualan_jasaservice dpjs ON (dpjs.id_detail_penjualan = dp.id)
                             WHERE YEAR(p.tgl_transaksi) = ? AND p.status = 3
                             GROUP BY bulan
                            ) AS T
                       GROUP BY bulan
                      ) AS B ON B.bulan = A.bulan
            ORDER BY A.bulan
        ', [$request->tahun, $request->tahun]);

        return $this->response->make();
    }

    public function pendapatan_tahunan(Request $request)
    {
        $this->response->data = DB::select('
            SELECT YEAR(p.tgl_transaksi) AS tahun, c.nama AS cabang, SUM(p.total) AS total
            FROM penjualan p
            JOIN cabang c ON(p.id_cabang = c.id)
            WHERE p.status = 3
            GROUP BY tahun, cabang
        ', []);

        return $this->response->make();
    }

    public function pengeluaran_bulanan(Request $request)
    {
        $this->response->data = DB::select('
            SELECT A.bulan, jumlah_pengeluaran
            FROM (SELECT 1 AS bulan
                  UNION SELECT 2 AS bulan
                  UNION SELECT 3 AS bulan
                  UNION SELECT 4 AS bulan
                  UNION SELECT 5 AS bulan
                  UNION SELECT 6 AS bulan
                  UNION SELECT 7 AS bulan
                  UNION SELECT 8 AS bulan
                  UNION SELECT 9 AS bulan
                  UNION SELECT 10 AS bulan
                  UNION SELECT 11 AS bulan
                  UNION SELECT 12 AS bulan
                 ) AS A
            LEFT JOIN (SELECT MONTH(tgl_transaksi) AS bulan, SUM(total) AS jumlah_pengeluaran
                       FROM pengadaan_barang
                       GROUP BY bulan
                      ) AS B ON B.bulan = A.bulan
            ORDER BY A.bulan
        ', []);

        return $this->response->make();
    }

    public function penjualan_jasa(Request $request)
    {
        $this->response->data = DB::select('
            SELECT k.merk AS merk, k.tipe AS tipe, js.nama AS nama_service, SUM(dpjs.jumlah) AS jumlah_penjualan
            FROM penjualan p
            JOIN detail_penjualan dp ON(dp.id_penjualan = p.id)
            JOIN detail_penjualan_jasaservice dpjs ON(dpjs.id_detail_penjualan = dp.id)
            JOIN jasa_service js ON(dpjs.id_jasaservice = js.id)
            JOIN kendaraan k ON(dp.nomor_polisi = k.nomor_polisi)
            WHERE YEAR(p.tgl_transaksi) = ? AND MONTH(p.tgl_transaksi) = ?
            GROUP BY merk, tipe, nama_service
            ORDER BY merk, tipe, nama_service
        ', [$request->tahun, $request->bulan]);

        return $this->response->make();

    }

    public function sisa_stok(Request $request)
    {
        $this->response->data = DB::select('
            SELECT A.bulan, sisa_stok
            FROM (SELECT 1 AS bulan
                  UNION SELECT 2 AS bulan
                  UNION SELECT 3 AS bulan
                  UNION SELECT 4 AS bulan
                  UNION SELECT 5 AS bulan
                  UNION SELECT 6 AS bulan
                  UNION SELECT 7 AS bulan
                  UNION SELECT 8 AS bulan
                  UNION SELECT 9 AS bulan
                  UNION SELECT 10 AS bulan
                  UNION SELECT 11 AS bulan
                  UNION SELECT 12 AS bulan
                 ) AS A
            LEFT JOIN (SELECT MONTH(hb.tgl_transaksi) AS bulan, (SUM(hb.masuk)-SUM(hb.keluar)) AS sisa_stok
                       FROM histori_barang hb
                       JOIN spareparts s ON (hb.kode_spareparts = s.kode)
                       WHERE YEAR(hb.tgl_transaksi) = ? AND s.tipe = ?
                       GROUP BY bulan
                      ) AS B ON B.bulan = A.bulan
            ORDER BY A.bulan
        ', [$request->tahun, $request->tipe_barang]);

        return $this->response->make();
    }
}
