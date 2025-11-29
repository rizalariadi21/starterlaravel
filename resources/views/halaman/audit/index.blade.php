@extends('layouts.default')
@section('title', 'Audit Logs')

@push('css')
	<link href="/assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
	<link href="/assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" />
@endpush

@push('scripts')
	<script src="/assets/plugins/datatables.net/js/dataTables.min.js"></script>
	<script src="/assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
	<script src="/assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <script>
    $(function(){
        var apiToken = '{{ Auth::check() ? (Auth::user()->api_token ?? '') : '' }}';
        var table = $('#audit-table').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[1, 'desc']],
            dom: '<"row mb-2"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-md-end justify-content-start"f>>rt<"row mt-2"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7 d-flex justify-content-md-end justify-content-start"p>>',
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/v1/audit-logs',
                type: 'POST',
                headers: apiToken ? { 'Authorization': 'Bearer ' + apiToken } : {},
                data: function (d) { return d; }
            },
            columns: [
                { data: null, orderable: false, searchable: false, render: function(data, type, row, meta){ return meta.row + 1 + meta.settings._iDisplayStart; } },
                { data: 0 },
                { data: 1 },
                { data: 2, render: function(data){ var a=(data||'').toLowerCase(); var badge='bg-secondary', text='text-white'; if(a==='create'||a==='insert'){badge='bg-success';} else if(a==='update'){badge='bg-warning'; text='text-white';} else if(a==='delete'){badge='bg-danger';} return '<span class="badge '+badge+' '+text+' text-uppercase">'+(data||'')+'</span>'; } },
                { data: 3 },
                { data: 4 },
                { data: 5 },
                { data: 6, render: function(data){ return '<span class="text-truncate" style="max-width:240px; display:inline-block">'+(data||'')+'</span>'; } },
                { data: 7, render: function(data){ return '<span class="text-truncate" style="max-width:280px; display:inline-block">'+(data||'')+'</span>'; } },
                { data: 8, render: function(data){ return '<span class="text-truncate" style="max-width:280px; display:inline-block">'+(data||'')+'</span>'; } }
            ]
        });
    });
    </script>
@endpush

@section('content')
<ol class="breadcrumb float-xl-end">
  <li class="breadcrumb-item"><a href="/">Home</a></li>
  <li class="breadcrumb-item active">Audit Logs</li>
 </ol>
<h1 class="page-header">Audit Logs <small>Riwayat perubahan data</small></h1>

<div class="panel panel-inverse">
  <div class="panel-heading">
    <h4 class="panel-title">Daftar Audit</h4>
  </div>
  <div class="panel-body">
    <div class="table-responsive">
      <table id="audit-table" width="100%" class="table table-striped table-bordered table-hover align-middle text-nowrap">
        <thead>
          <tr>
            <th width="1%">#</th>
            <th class="text-nowrap">Waktu</th>
            <th class="text-nowrap">Tabel</th>
            <th class="text-nowrap">Aksi</th>
            <th class="text-nowrap">Primary Key</th>
            <th class="text-nowrap">Actor</th>
            <th class="text-nowrap">IP</th>
            <th class="text-nowrap">URL</th>
            <th class="text-nowrap">Before</th>
            <th class="text-nowrap">After</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>
@endsection
