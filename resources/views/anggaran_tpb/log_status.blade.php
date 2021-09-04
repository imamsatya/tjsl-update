<table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_log">
    <thead>
        <tr>
            <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">No.</th>
            <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">User</th>
            <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Anggaran</th>
            <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Status </th>
            <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Waktu</th>
            <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @php $no=1; @endphp
        @foreach($log as $p)
        <tr>
            <td>{{$no++}}</td>
            <td>{{@$p->user->username}}</td>
            <td>{{number_format(@$p->anggaran,0,',',',')}}</td>
            <td>{{@$p->status->nama}}</td>
            <td>{{@$p->created_at}}</td>
            <td>{{@$p->keterangan}}</td>
        </tr>
        @endforeach
        @if(count($log) == 0)
        <tr>
            <td colspan="4" style="text-align:center;font-style:italic">Tidak ada data</td>
        </tr>
        @endif
    </tbody>
</table>

<script type="text/javascript">
    var title = "{{ $pagetitle }}";

    $(document).ready(function(){
        $('.modal-title').html(title);
        $('.form-select2').select2();
    });
</script>
