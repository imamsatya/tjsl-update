<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ZipArchive;
use Illuminate\Support\Facades\Log;
use DB;
use App\Models\DownloadKegiatanZip;
class ZipKegiatanFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 5;
    protected $filesToZip;
    protected $downloadKegiatanZipId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filesToZip, $downloadKegiatanZipId)
    {
        //
        $this->filesToZip = $filesToZip;
        $this->downloadKegiatanZipId = $downloadKegiatanZipId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $filesToZip = $this->filesToZip;
        $downloadKegiatanZipId = $this->downloadKegiatanZipId;
        Log::info('ZipMitraFIles Job');
        Log::info('filesToZip', $filesToZip);
        Log::info('downloadKegiatanZipId', ['value' => $downloadKegiatanZipId]);

         // Zip the exported file
         $zipFileName = "Kegiatan_Export_{$downloadKegiatanZipId}.zip";
         $zipFilePath = storage_path("app/public/zip_kegiatan/{$zipFileName}");
         Log::info('zipFIleName', ['value' => $zipFileName]);
         Log::info('zipFilePath', ['value' => $zipFilePath]);
         $zip = new ZipArchive;
         Log::info('zip', ['value' => $zip]);
       
         if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            Log::info('creating zip');
            //create zip in the files in filesToZip array
            $files = DB::table('download_kegiatan_exports')->whereIn('id', $filesToZip)->get()->pluck('file_path');
            foreach ($files as $file) {
                $filePath = storage_path('app/public/download_kegiatan/' . $file);
                $zip->addFile($filePath, basename($filePath));
            }
            // public/download_kegiatan/

            $zip->close();

            $latestZip = DownloadKegiatanZip::find($downloadKegiatanZipId);
            $latestZip->status = 'done';
            $latestZip->file_path = $zipFileName; // 'app/public/download/'
            $latestZip->updated_at = date('Y-m-d H:i:s');
            $latestZip->save();
            // return response()->download(public_path($zipFileName))->deleteFileAfterSend(true);
            return "Zip file created successfully at {$zipFilePath}";
        } else {
                 Log::info('failed');
            return "Failed to create the zip file.";
        }
    }
}
