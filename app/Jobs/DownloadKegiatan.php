<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class DownloadKegiatan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $request = $this->data;  
    
        $downloadId = $request['downloadId']; 
        $latestDownload = DownloadKegiatanExport::find($downloadId);
        $latestDownload->status = 'on process';
        $latestDownload->updated_at = date('Y-m-d H:i:s');
        $latestDownload->save();
        //
        $perusahaan_id = $request->perusahaan_id ?? 'all';
        $bulan = $request->bulan;
        $tahun = $request->tahun ?? date('Y');
        $jenis_anggaran = $request->jenis_anggaran ?? 'CID';
        // dd($perusahaan_id);
        $kegiatan = DB::table('kegiatans')
        ->join('kegiatan_realisasis', function($join) use ($bulan, $tahun) {
            $join->on('kegiatan_realisasis.kegiatan_id', '=', 'kegiatans.id')
                ->where(function($query) use ($bulan) {
                    if ($bulan !== null) {
                        $query->where('kegiatan_realisasis.bulan', $bulan);
                    }
                })
                ->where('kegiatan_realisasis.tahun', $tahun);
        })
        ->join('bulans', 'bulans.id', 'kegiatan_realisasis.bulan')
        ->join('target_tpbs', 'target_tpbs.id', 'kegiatans.target_tpb_id')
        ->join('anggaran_tpbs', function($join) use ($perusahaan_id, $tahun) {
            if ($perusahaan_id != 'all') {
                $join->on('anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                ->where('anggaran_tpbs.tahun', $tahun);
            }

            if ($perusahaan_id == 'all') {
                $join->on('anggaran_tpbs.id', '=', 'target_tpbs.anggaran_tpb_id')
                // ->where('anggaran_tpbs.perusahaan_id', $perusahaan_id)
                ->where('anggaran_tpbs.tahun', $tahun);
            }
            
        })
        ->join('relasi_pilar_tpbs', 'relasi_pilar_tpbs.id', '=', 'anggaran_tpbs.relasi_pilar_tpb_id')
    
        ->join('tpbs', function($join) use ($jenis_anggaran) {
            $join->on('tpbs.id', '=', 'relasi_pilar_tpbs.tpb_id')
                ->where('tpbs.jenis_anggaran', $jenis_anggaran);
        })
        ->leftJoin('jenis_kegiatans', 'jenis_kegiatans.id', '=', 'kegiatans.jenis_kegiatan_id')
        ->leftjoin('sub_kegiatans', 'sub_kegiatans.id', '=', DB::raw('CAST(kegiatans.keterangan_kegiatan AS BIGINT)'))
        ->join('provinsis', 'provinsis.id', '=', 'kegiatans.provinsi_id')
        ->join('kotas', 'kotas.id', '=', 'kegiatans.kota_id')
        ->join('satuan_ukur', 'satuan_ukur.id', '=', 'kegiatans.satuan_ukur_id')
        ->join('statuses', 'statuses.id', '=', 'kegiatan_realisasis.status_id')
        ->join('perusahaan_masters', 'perusahaan_masters.id', '=', 'anggaran_tpbs.perusahaan_id')
        ->join('pilar_pembangunans', 'pilar_pembangunans.id', '=', 'relasi_pilar_tpbs.pilar_pembangunan_id')
        ->select(
            'kegiatans.*',
            'kegiatan_realisasis.bulan as kegiatan_realisasi_bulan',
            'kegiatan_realisasis.tahun as kegiatan_realisasi_tahun',
            'kegiatan_realisasis.realisasi as kegiatan_realisasi_realisasi', 
            'kegiatan_realisasis.anggaran as kegiatan_realisasi_anggaran',
            'kegiatan_realisasis.anggaran_total as kegiatan_realisasi_anggaran_total',
            'kegiatan_realisasis.status_id as kegiatan_realisasi_status_id',
            'target_tpbs.id as target_tpb_id',
            'target_tpbs.program as target_tpb_program',
            'jenis_kegiatans.nama as jenis_kegiatan_nama',
            'sub_kegiatans.subkegiatan as sub_kegiatan_nama',
            'provinsis.nama as provinsi_nama',
            'kotas.nama as kota_nama',
            'anggaran_tpbs.id as anggaran_tpb_id',
            'relasi_pilar_tpbs.id as relasi_pilar_tpb_id',
            'tpbs.id as tpb_id',
            'tpbs.nama as tpb_nama',
            'tpbs.jenis_anggaran',
            'satuan_ukur.nama as satuan_ukur_nama',
            'bulans.nama as bulan_nama',
            'statuses.nama as nama_status',
            'perusahaan_masters.nama_lengkap as perusahaan_nama_lengkap',
            'pilar_pembangunans.nama as pilar_pembangunan_nama',
            

        );

        if ($request->pilar_pembangunan_id) {

            $kegiatan = $kegiatan->where('relasi_pilar_tpbs.pilar_pembangunan_id', $request->pilar_pembangunan_id);
        }

        if ($request->tpb_id) {

            $kegiatan = $kegiatan->where('tpbs.id', $request->tpb_id);
        }

        if ($request->program_id) {

            $kegiatan = $kegiatan->where('target_tpbs.id', $request->program_id);
        }

        if ($request->jenis_kegiatan) {

            $kegiatan = $kegiatan->where('jenis_kegiatans.id', $request->jenis_kegiatan);
        }

        $kegiatan = $kegiatan->get();


        $namaFile = "Kegiatan ".date('dmY').".xlsx";
        // return Excel::download(new KegiatanBulanExport($kegiatan, $request->tahun), $namaFile);   

        Excel::store(new KegiatanBulanExport($kegiatan, $request->tahun), 'public/download_kegiatan/'.$namaFile);

        $latestDownload = DownloadKegiatanExport::find($downloadId);
        $latestDownload->status = 'done';
        $latestDownload->file_path = $namaFile; // 'app/public/download/'
        $latestDownload->updated_at = date('Y-m-d H:i:s');
        $latestDownload->save();
    }
}
