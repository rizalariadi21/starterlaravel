@extends('layouts.default')
@section('title', 'Pengguna')

@push('css')
	<link href="/assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
	<link href="/assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css" rel="stylesheet" />
	<link href="/assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css" rel="stylesheet" />
@endpush

@push('scripts')
	<script src="/assets/plugins/datatables.net/js/dataTables.min.js"></script>
	<script src="/assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
	<script src="/assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
	<script src="/assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
	<script src="/assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
	<script src="/assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
	<script src="/assets/plugins/datatables.net-buttons/js/buttons.html5.min.js"></script>
	<script src="/assets/plugins/datatables.net-buttons/js/buttons.print.min.js"></script>
	<script src="/assets/plugins/pdfmake/build/pdfmake.min.js"></script>
	<script src="/assets/plugins/pdfmake/build/vfs_fonts.js"></script>
	<script src="/assets/plugins/jszip/dist/jszip.min.js"></script>
    <script>
    $(function(){
        var apiToken = '{{ Auth::check() ? (Auth::user()->api_token ?? '') : '' }}';
        $('#data-table-default').DataTable({
            responsive: true,
            pageLength: 10,
            dom: '<"row mb-2"<"col-sm-12 col-md-6"lB><"col-sm-12 col-md-6 d-flex justify-content-md-end justify-content-start"f>>rt<"row mt-2"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7 d-flex justify-content-md-end justify-content-start"p>>',
            buttons: ['excel'],
            processing: true,
            serverSide: true,
            ajax: {
                url: '/api/v1/pengguna',
                type: 'POST',
                headers: apiToken ? { 'Authorization': 'Bearer ' + apiToken } : {},
                data: function(d){ return d; }
            },
            columns: [
                { data: null, orderable: false, searchable: false, render: function(data, type, row, meta){ return meta.row + 1 + meta.settings._iDisplayStart; } },
                { data: 1 },
                { data: 4 },
                { data: 2 },
                { data: 3 },
                { data: 5, render: function(data){
                    var html = '';
                    if (data == 1) html = '<span class="badge bg-danger">Admin</span>';
                    else if (data == 2) html = '<span class="badge bg-success">User</span>';
                    else if (data == 3) html = '<span class="badge bg-warning">Viewer</span>';
                    else html = data;
                    return html;
                } },
                { data: 6, render: function(data){ return data==1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Nonaktif</span>'; } },
                { data: 7 },
                { data: 8 },
                { data: 0, orderable: false, searchable: false, render: function(id){
                    var csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
                    var editUrl = '/pengguna/' + id + '/edit';
                    var delUrl = '/pengguna/' + id;
                    var form = '<form action="'+delUrl+'" method="POST" class="d-inline" onsubmit="return confirm(\'Hapus pengguna ini?\')">'
                             + '<input type="hidden" name="_token" value="'+csrf+'" />'
                             + '<input type="hidden" name="_method" value="DELETE" />'
                             + '<button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Hapus</button>'
                             + '</form>';
                    var edit = '<a href="'+editUrl+'" class="btn btn-sm btn-warning me-1"><i class="fa fa-edit"></i> Edit</a>';
                    return edit + form;
                } }
            ]
        });
    });
    </script>
@endpush
@section('content')
<ol class="breadcrumb float-xl-end">
  <li class="breadcrumb-item"><a href="/">Home</a></li>
  <li class="breadcrumb-item active">Pengguna</li>
</ol>
<h1 class="page-header">Pengguna <small>Manage data pengguna</small></h1>

@if(session('status'))
<div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="panel panel-inverse">
  <div class="panel-heading">
    <h4 class="panel-title">Daftar Pengguna</h4>
    <div class="panel-heading-btn">
      <a href="{{ route('pengguna-create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Tambah</a>
    </div>
  </div>
  <div class="panel-body">
    <div class="table-responsive">
      <table id="data-table-default" width="100%" class="table table-striped table-bordered align-middle text-nowrap">
        <thead>
          <tr>
            <th width="1%"></th>
            <th class="text-nowrap" data-orderable="false">Pengguna</th>
            <th class="text-nowrap" data-orderable="false">Username</th>
            <th class="text-nowrap" data-orderable="false">Institusi</th>
            <th class="text-nowrap" data-orderable="false">No HP</th>
            <th class="text-nowrap" data-orderable="false">Peran</th>
            <th class="text-nowrap" data-orderable="false">Status</th>
            <th class="text-nowrap" data-orderable="false">Created</th>
            <th class="text-nowrap" data-orderable="false">Updated</th>
            <th class="text-nowrap" width="160" data-orderable="false">Aksi</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
  </div>
@endsection
