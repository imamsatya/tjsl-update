<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TargetTpb extends Model
{
    protected $guarded = [];

    public function status()
    {
    	return $this->belongsTo('App\Models\Status', 'status_id');
    }

    public function anggaran_tpb()
    {
    	return $this->belongsTo('App\Models\AnggaranTpb', 'anggaran_tpb_id');
    }
    
    public function tpb()
    {
    	return $this->belongsTo('App\Models\Tpb', 'tpb_id');
    }

    public function jenis_program()
    {
    	return $this->belongsTo('App\Models\JenisProgram', 'jenis_program_id');
    }
    
    public function core_subject()
    {
    	return $this->belongsTo('App\Models\CoreSubject', 'core_subject_id');
    }

    public function kode_indikator()
    {
    	return $this->belongsTo('App\Models\KodeIndikator', 'kode_indikator_id');
    }

    public function kode_tujuan_tpb()
    {
    	return $this->belongsTo('App\Models\KodeTujuanTpb', 'kode_tujuan_tpb_id');
    }
    
    public function cara_penyaluran()
    {
    	return $this->belongsTo('App\Models\CaraPenyaluran', 'cara_penyaluran_id');
    }

    public function mitra_bumn()
    {
        return $this->belongsToMany('App\Models\Perusahaan','App\Models\TargetMitra','target_tpb_id','perusahaan_id');
    }
}
