<ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_4">CID</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_5">NON CID</a>
    </li>    
</ul>

<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="kt_tab_pane_4" role="tabpanel">
        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_log">
            <thead>
                <tr>
                    <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">No.</th>
                    <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">User</th>
                    <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Anggaran</th>
                    <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Keterangan</th>
                    <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Status </th>
                    <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @php $no=1; @endphp
                @foreach($log_cid as $p)
                <tr>
                    <td>{{$no++}}</td>
                    <td>{{@$p->user->username}}</td>
                    <td style="text-align:right;">{{number_format(@$p->anggaran,0,',',',')}}</td>
                    <td>{{@$p->keterangan}}</td>
                    <td>{{@$p->status->nama}}</td>
                    <td>{{@$p->created_at}}</td>
                </tr>
                @endforeach
                @if(count($log_cid) == 0)
                <tr>
                    <td colspan="4" style="text-align:center;font-style:italic">Tidak ada data</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="tab-pane fade" id="kt_tab_pane_5" role="tabpanel">
        <table class="table table-striped- table-bordered table-hover table-checkable" id="datatable_log">
            <thead>
                <tr>
                    <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">No.</th>
                    <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">User</th>
                    <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Anggaran</th>
                    <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Keterangan</th>
                    <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Status </th>
                    <th style="font-weight:bold;border-bottom: 1px solid #c8c7c7;">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @php $no=1; @endphp
                @foreach($log_noncid as $p)
                <tr>
                    <td>{{$no++}}</td>
                    <td>{{@$p->user->username}}</td>
                    <td style="text-align:right;">{{number_format(@$p->anggaran,0,',',',')}}</td>
                    <td>{{@$p->keterangan}}</td>
                    <td>{{@$p->status->nama}}</td>
                    <td>{{@$p->created_at}}</td>
                </tr>
                @endforeach
                @if(count($log_noncid) == 0)
                <tr>
                    <td colspan="4" style="text-align:center;font-style:italic">Tidak ada data</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div> 
</div>



<script type="text/javascript">
    var title = "{{ $pagetitle }}";

    $(document).ready(function(){
        $('.modal-title').html(title);
        $('.form-select2').select2();
    });
</script>
