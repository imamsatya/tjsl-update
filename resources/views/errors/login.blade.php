<!DOCTYPE html>
<html lang="id" dir="ltr">

<head>
     <meta charset="utf-8" />
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
     <meta name="description" content="" />
     <meta name="author" content="" />

     <!-- Title -->
     <title>Forbidden!</title>
     <link rel="shortcut icon" href="{{ asset('/assets/media/logos/favicon.ico') }}" />
     <link href="{{ asset('/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
     <link href="{{ asset('/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
    #footer{
     position: fixed;
     bottom: 20px
}
</style>
</head>

<body class="bg-dark text-white py-10">
     <div class="container py-5">
          <div class="row">
               <div class="col-md-12 text-center">
                <p><i class="fa fa-exclamation-triangle fa-8x" style="color: yellow;"></i><br/><b>403</b></p>
                    <h3>Ooppss!!!...</h3>
                    <p>Maaf sepertinya akun anda belum terdaftar untuk akses portal ini.<br/>Hubungi admin/Helpdesk IT untuk registrasi akun.</p>
                    <a class="btn btn-danger" href="{{ url('/') }}"><< Kembali</a>
               </div>
          </div>
     </div>

     <div id="footer" class="col-md-12 text-center">
          Admin Portal TJSL Kementerian BUMN @ 2021
     </div>
</body>

</html>