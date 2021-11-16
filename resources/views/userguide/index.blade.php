@extends('layouts.app')

@section('content')

<div class="post d-flex flex-column-fluid cls-content-data" id="kt_content">
    <div id="kt_content_container" class="container">
        <div class="card">
            <div class="card-header pt-5">
                <div class="card-title">
                    {{-- <h2 class="d-flex align-items-center">{{ $pagetitle }}
                    <span class="text-gray-600 fs-6 ms-1"></span></h2> --}}

                    <h2 class="d-flex align-items-center">Panduan Penggunaan
                    <span class="text-gray-600 fs-6 ms-1"></span></h2>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="card-px py-10">
                    {{-- <div class="row">
                        <div class="col-md-8">
                            <p>Silahkan Klik Tombol berikut untuk mendownload Panduan Penggunaan</p>
                        </div>
                        <div class="col-md-4">
                            <a href="" class="btn btn-primary"> Download</a>
                        </div>
                    </div> --}}
                    <p>Silahkan Klik Tombol di bawah ini untuk mendownload Panduan Penggunaan</p> <a href="https://bit.ly/3qoSCBY" target="_blank" class="btn btn-primary"> Download</a>
                </div>
            </div>
           
        </div>
    </div>
</div>
@endsection

@section('addafterjs')
<script>

    $(document).ready(function(){
        // $('#page-title').html("{{ $pagetitle }}");
        $('#page-breadcrumb').html("{{ $breadcrumb }}");
    });

</script>
@endsection

