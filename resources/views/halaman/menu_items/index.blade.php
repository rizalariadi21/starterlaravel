@extends('layouts.default')
@section('title', 'Menu Items')

@section('content')
<ol class="breadcrumb float-xl-end">
  <li class="breadcrumb-item"><a href="/">Home</a></li>
  <li class="breadcrumb-item active">Menu Items</li>
</ol>
<h1 class="page-header">Menu Items <small>Kelola menu (CRUD + drag sort)</small></h1>

<div id="pageAlert" class="alert alert-success d-none" role="alert"></div>

<div class="row">
  <div class="col-xl-6">
    <div class="panel panel-inverse">
      <div class="panel-heading">
        <h4 class="panel-title">Struktur Menu</h4>
      </div>
      <div class="panel-body">
        <div id="menuTree"></div>
      </div>
    </div>
  </div>
  <div class="col-xl-6">
    <div class="panel panel-inverse">
      <div class="panel-heading">
        <h4 class="panel-title">Detail Item</h4>
      </div>
      <div class="panel-body">
        <form id="menuItemForm">
          <input type="hidden" name="id" id="mi-id" />
          <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" class="form-control" name="title" id="mi-title" />
          </div>
          <div class="mb-3">
            <label class="form-label">Icon (Ionicons)</label>
            <div class="input-group">
              <input type="text" class="form-control" name="icon" id="mi-icon" placeholder="ion:home" />
              <button type="button" class="btn btn-outline-secondary" id="btn-icon-picker">Cari Icon</button>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">URL</label>
            <input type="text" class="form-control" name="url" id="mi-url" placeholder="/path atau javascript:;" />
          </div>
          <div class="mb-3">
            <label class="form-label">Route Name</label>
            <input type="text" class="form-control" name="route_name" id="mi-route" />
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" value="1" id="mi-caret" name="caret">
            <label class="form-check-label" for="mi-caret">Tampilkan caret (punya sub menu)</label>
          </div>
          <div class="mb-3">
            <label class="form-label">Levels Diizinkan</label>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="1" id="lv1" name="levels[]">
              <label class="form-check-label" for="lv1">Admin (1)</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="2" id="lv2" name="levels[]">
              <label class="form-check-label" for="lv2">User (2)</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="3" id="lv3" name="levels[]">
              <label class="form-check-label" for="lv3">Viewer (3)</label>
            </div>
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" id="btn-reset" class="btn btn-default">Reset</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('css')
  <link href="/assets/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" />
  <style>
    #icon-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 10px; }
    .icon-item { border: 1px solid rgba(0,0,0,.1); border-radius: 6px; padding: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    .icon-item:hover { background: rgba(0,0,0,.04); }
    #gradient-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 10px; }
    .gradient-item { border: 1px solid rgba(0,0,0,.1); border-radius: 6px; padding: 10px; cursor: pointer; height: 48px; display: flex; align-items: center; justify-content: center; }
    .gradient-item span { font-size: 12px; background: none; padding: 2px 6px; border-radius: 10px; }
  </style>
@endpush

@push('scripts')
  <script src="/assets/plugins/jstree/dist/jstree.min.js"></script>
  <script>
  const csrfToken = '{{ csrf_token() }}';
  const initialNodes = @json($nodes);
  const icons = @json($icons);
  const gradients = @json($gradients);
  let lastLoaded = {};
  function renderIcons(filter){
    const list = !filter ? icons : icons.filter(name => name.includes(filter));
    const cont = document.getElementById('icon-grid');
    cont.innerHTML = '';
    list.slice(0, 1000).forEach(name => {
      const div = document.createElement('div');
      div.className = 'icon-item';
      div.innerHTML = '<ion-icon name="'+ name +'" class="fs-2"></ion-icon>';
      div.onclick = function(){ document.getElementById('mi-icon').value = 'ion:'+ name; bootstrap.Modal.getInstance(document.getElementById('modalIconPicker')).hide(); showToast('Icon dipilih'); };
      cont.appendChild(div);
    });
  }

  function renderGradients(){
    const cont = document.getElementById('gradient-grid');
    if (!cont) return;
    cont.innerHTML = '';
    gradients.forEach(cls => {
      const div = document.createElement('div');
      div.className = 'gradient-item '+ cls;
      div.innerHTML = '<span class="text-gradient '+ cls +'">'+ cls +'</span>';
      div.onclick = function(){ setGradient(cls); showToast('Warna diterapkan'); };
      cont.appendChild(div);
    });
  }

  function setGradient(cls){
    const input = document.getElementById('mi-icon');
    const val = (input.value || '').trim();
    const parts = val.split(/\s+/).filter(Boolean);
    const filtered = parts.filter(p => !/^bg-gradient-/.test(p) && p !== 'text-gradient');
    filtered.push(cls);
    input.value = filtered.join(' ');
  }

  function showToast(msg) {
    const el = document.getElementById('saveToast');
    if (!el) return alert(msg);
    el.querySelector('.toast-body').textContent = msg;
    new bootstrap.Toast(el).show();
  }

  function levelsToArray(levels) {
    if (!levels) return [];
    return (Array.isArray(levels) ? levels : []).map(v => parseInt(v)).filter(v => !isNaN(v));
  }

  function fillForm(data) {
    document.getElementById('mi-id').value = data.id || '';
    document.getElementById('mi-title').value = data.title || '';
    document.getElementById('mi-icon').value = data.icon || '';
    document.getElementById('mi-url').value = data.url || '';
    document.getElementById('mi-route').value = data.route_name || '';
    document.getElementById('mi-caret').checked = !!data.caret;
    const lv = levelsToArray(data.levels);
    ['lv1','lv2','lv3'].forEach(id => { document.getElementById(id).checked = false; });
    lv.forEach(v => { const el = document.getElementById('lv'+v); if (el) el.checked = true; });
    lastLoaded = data || {};
  }

  function getFormPayload() {
    const id = document.getElementById('mi-id').value;
    const title = document.getElementById('mi-title').value;
    const icon = document.getElementById('mi-icon').value;
    const url = document.getElementById('mi-url').value;
    const route_name = document.getElementById('mi-route').value;
    const caret = document.getElementById('mi-caret').checked ? 1 : 0;
    const levels = [];
    ['lv1','lv2','lv3'].forEach(id => { const el = document.getElementById(id); if (el.checked) levels.push(parseInt(el.value)); });
    return { id, title, icon, url, route_name, caret, 'levels[]': levels };
  }

  $(function() {
    const msgAfterReload = localStorage.getItem('menuItemsToast');
    if (msgAfterReload) {
      const el = document.getElementById('pageAlert');
      if (el) { el.textContent = msgAfterReload; el.classList.remove('d-none'); }
      localStorage.removeItem('menuItemsToast');
    }
    const treeEl = document.getElementById('menuTree');
    $(treeEl).jstree({
      core: { data: initialNodes, check_callback: true },
      plugins: ['dnd','contextmenu','state','wholerow'],
      contextmenu: {
        items: function(node){
          const inst = $.jstree.reference(treeEl);
          return {
            create: { label: 'Tambah', action: function(){
              const newNode = inst.create_node(node, { text: 'Item Baru' }, 'last');
            }},
            rename: { label: 'Ubah Judul', action: function(){ inst.edit(node); }},
            remove: { label: 'Hapus', action: function(){ inst.delete_node(node); }}
          };
        }
      }
    })
    .on('create_node.jstree', function(e, data){
      $.ajax({
        method: 'POST', url: '/menu-items', data: {
          _token: csrfToken, parent_id: (data.parent === '#' ? null : data.parent), position: data.position, title: data.node.text
        }}).done(function(resp){
          const inst = $.jstree.reference(treeEl);
          inst.set_id(data.node, String(resp.id));
        }).fail(function(){ alert('Gagal membuat item'); });
    })
    .on('rename_node.jstree', function(e, data){
      $.ajax({ method: 'PUT', url: '/menu-items/'+data.node.id, data: { _token: csrfToken, title: data.text } })
       .fail(function(){ alert('Gagal mengubah judul'); });
    })
    .on('delete_node.jstree', function(e, data){
      $.ajax({ method: 'POST', url: '/menu-items/'+data.node.id, data: { _method: 'DELETE', _token: csrfToken } })
       .fail(function(){ alert('Gagal menghapus'); });
    })
    .on('move_node.jstree', function(e, data){
      $.ajax({ method: 'POST', url: '/menu-items/move', data: { _token: csrfToken, id: data.node.id, parent: (data.parent === '#' ? null : data.parent), position: data.position } })
       .fail(function(){ alert('Gagal memindahkan'); });
    })
    .on('select_node.jstree', function(e, data){
      $.getJSON('/menu-items/'+data.node.id, function(resp){ fillForm(resp); });
    });

    document.getElementById('menuItemForm').addEventListener('submit', function(ev){
      ev.preventDefault();
      const payload = getFormPayload();
      if (!payload.id) { alert('Pilih item terlebih dahulu'); return; }
    $.ajax({ method: 'PUT', url: '/menu-items/'+payload.id, data: Object.assign({ _token: csrfToken }, payload) })
      .done(function(){
        const changedIcon = (payload.icon || '') !== (lastLoaded.icon || '');
        localStorage.setItem('menuItemsToast', changedIcon ? 'Icon telah di update' : 'Data menu telah diperbarui');
        location.reload();
      })
      .fail(function(xhr){
        var msg = 'Gagal menyimpan';
        try {
          if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
        } catch(e) {}
        showToast(msg);
      });
    });

    document.getElementById('btn-reset').addEventListener('click', function(){ fillForm({}); });
    document.getElementById('btn-icon-picker').addEventListener('click', function(){
      renderIcons('');
      renderGradients();
      new bootstrap.Modal(document.getElementById('modalIconPicker')).show();
    });
    document.getElementById('icon-search').addEventListener('input', function(){ renderIcons(this.value.trim().toLowerCase()); });
  });
  </script>
  <div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="saveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <i class="fa fa-bell me-2"></i>
        <strong class="me-auto">Notifikasi</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">Berhasil</div>
    </div>
  </div>
  <div class="modal fade" id="modalIconPicker" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Pilih Icon Ionicons</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <ul class="nav nav-pills mb-3">
            <li class="nav-item"><a href="#tab-icons" data-bs-toggle="tab" class="nav-link active">Icon</a></li>
            <li class="nav-item ms-2"><a href="#tab-gradients" data-bs-toggle="tab" class="nav-link">Warna Gradient</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-icons">
              <div class="mb-3">
                <input type="text" class="form-control" id="icon-search" placeholder="Cari icon (mis. home, home-outline, logo-apple)" />
              </div>
              <div id="icon-grid"></div>
            </div>
            <div class="tab-pane fade" id="tab-gradients">
              <div class="mb-2">Pilih Warna Gradient</div>
              <div id="gradient-grid"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
@endpush
@endsection
