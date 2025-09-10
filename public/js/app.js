// Dependencies: jQuery, SortableJS, SweetAlert2, Bootstrap
let CURRENT_BOARD_ID = null;
let OVERDUE_SEEN = new Set(); // prevent duplicate alerts
let CURRENT_USER_ROLE = '<?php echo $_SESSION["role"] ?? "user"; ?>'; // placeholder, will be set server-side by PHP include

function toast(icon, title) {
  Swal.fire({toast:true, position:'top-end', timer:2000, showConfirmButton:false, icon, title});
}

function playBeep(){
  try {
    const ctx = new (window.AudioContext || window.webkitAudioContext)();
    const o = ctx.createOscillator();
    const g = ctx.createGain();
    o.type = 'sine';
    o.frequency.value = 880;
    g.gain.value = 0.05;
    o.connect(g); g.connect(ctx.destination);
    o.start();
    setTimeout(()=>{ o.stop(); ctx.close(); }, 400);
  } catch(e){ console.log('Beep not supported', e); }
}

function loadBoards() {
  $.get('api/board_list.php', function(res) {
    const wrap = $('#boardList'); wrap.empty();
    res.data.forEach(b => {
      wrap.append(`<li class="list-group-item d-flex justify-content-between align-items-center pointer" data-id="${b.id}">
        <span>${b.title}</span>
        <span class="badge bg-secondary rounded-pill">${b.total_lists}</span>
    </li>`);
    });
  });
}

function openBoard(id) {
  CURRENT_BOARD_ID = id;
  $('#boardArea').html('<div class="text-muted p-4">Loading board...</div>');
  $.get('api/subboard_list.php', {board_id:id}, function(res){
    let html = '<div class="row g-3">';
    res.data.forEach(sb => {
      html += `<div class="col-md-3">
        <div class="column" data-subboard-id="${sb.id}">
          <div class="column-header">
            <h6 class="mb-0 text-capitalize">${sb.name}</h6>
            <button class="btn btn-sm btn-outline-primary" onclick="showAddCard(${sb.id})">+ Card</button>
          </div>
          <div class="list-container" id="sb-${sb.id}"></div>
        </div>
    </div>`;
  });
    html += '</div>';
    $('#boardArea').html(html);
    // load cards
    $.get('api/list_get.php', {board_id:id}, function(res2){
      res2.data.forEach(c => renderCard(c));
      initDrag();
    });
  });
}

function renderCard(c){
  // labels: JSON string or null
  let labels = [];
  try { labels = c.labels ? JSON.parse(c.labels) : []; } catch(e){ labels = []; }
  const labelHtml = labels.map(l => `<span class="label-badge label-${l}">${l}</span>`).join('');
  const priorityClass = c.priority === 'high' ? 'badge-priority-high' : (c.priority === 'medium' ? 'badge-priority-medium' : 'badge-priority-low');
  const el = $(`<div class="draggable-card" draggable="true" data-id="${c.id}" data-review="${c.review_status}" data-subboard="${c.sub_board_id}">
      <div class="d-flex justify-content-between align-items-center">
        <strong>${c.title}</strong>
        <span class="badge ${priorityClass} card-badge text-uppercase">${c.priority}</span>
      </div>
    ${labelHtml ? `<div class="mt-1">${labelHtml}</div>` : ''}
    ${c.assignee ? `<div class="small text-muted">Assignee: ${c.assignee}</div>` : ''}
    ${c.deadline ? `<div class="small">Deadline: <span class="badge bg-${deadlineBadge(c.deadline)}">${c.deadline}</span></div>` : ''}
      <div class="mt-2 d-flex gap-2">
        <button class="btn btn-sm btn-outline-secondary" onclick="editCard(${c.id})">Edit</button>
        <button class="btn btn-sm btn-outline-danger" onclick="deleteCard(${c.id})">Delete</button>
      </div>
    ${c.review_status !== 'none' ? `<div class="mt-2"><span class="badge ${c.review_status==='approved'?'bg-success':(c.review_status==='revisi'?'bg-warning text-dark':'bg-info')}">Review: ${c.review_status}</span></div>` : ''}
  </div>`);
  $(`#sb-${c.sub_board_id}`).append(el);
}

function deadlineBadge(dt){
  const now = new Date();
  const d = new Date(dt.replace(' ','T'));
  return d < now ? 'danger' : 'secondary';
}

function showAddBoard(){
  $('#newBoardTitle').val('');
  const modal = new bootstrap.Modal('#boardModal'); modal.show();
}

function saveBoard(){
  const title = $('#newBoardTitle').val().trim();
  if(!title){ toast('error','Judul tidak boleh kosong'); return; }
  $.post('api/board_create.php', {title}, function(res){
    if(res.ok){
      toast('success','Board dibuat');
      loadBoards();
      bootstrap.Modal.getInstance(document.getElementById('boardModal')).hide();
    } else {
      toast('error', res.msg || 'Gagal membuat board');
    }
  }, 'json');
}

function showAddCard(subBoardId){
  $('#cardId').val('');
  $('#cardSubBoardId').val(subBoardId);
  $('#cardTitle').val('');
  $('#cardDesc').val('');
  $('#cardAssignee').val('');
  $('#cardPriority').val('low');
  $('#cardDeadline').val('');
  $('#cardReview').val('none');
  $('#cardReviewNotes').val('');
  // reset labels
  ['red','green','blue','yellow','purple'].forEach(l => $('#label_'+l).prop('checked', false));
  new bootstrap.Modal('#cardModal').show();
}

function editCard(id){
  $.get('api/list_get.php', {id}, function(res){
    const c = res.data[0];
    $('#cardId').val(c.id);
    $('#cardSubBoardId').val(c.sub_board_id);
    $('#cardTitle').val(c.title);
    $('#cardDesc').val(c.description);
    $('#cardAssignee').val(c.assignee);
    $('#cardPriority').val(c.priority);
    $('#cardDeadline').val(c.deadline ? c.deadline.replace(' ','T') : '');
    $('#cardReview').val(c.review_status);
    $('#cardReviewNotes').val(c.review_notes || '');
    // set labels
    let labels = [];
    try { labels = c.labels ? JSON.parse(c.labels) : []; } catch(e){ labels = []; }
    ['red','green','blue','yellow','purple'].forEach(l => $('#label_'+l).prop('checked', labels.indexOf(l) !== -1));
    new bootstrap.Modal('#cardModal').show();
  });
}

function saveCard(){
  const labels = ['red','green','blue','yellow','purple'].filter(l => $('#label_'+l).prop('checked'));
  const payload = {
    id: $('#cardId').val(),
    board_id: CURRENT_BOARD_ID,
    sub_board_id: $('#cardSubBoardId').val(),
    title: $('#cardTitle').val().trim(),
    description: $('#cardDesc').val(),
    assignee: $('#cardAssignee').val(),
    priority: $('#cardPriority').val(),
    deadline: $('#cardDeadline').val(),
    review_status: $('#cardReview').val(),
    review_notes: $('#cardReviewNotes').val(),
    labels: JSON.stringify(labels)
  };
  if(!payload.title){ toast('error','Judul card wajib'); return; }
  $.post('api/list_create.php', payload, function(res){
    if(res.ok){
      toast('success','Card disimpan');
      openBoard(CURRENT_BOARD_ID);
      bootstrap.Modal.getInstance(document.getElementById('cardModal')).hide();
    } else {
      toast('error', res.msg || 'Gagal simpan');
    }
  }, 'json');
}

function deleteCard(id){
  Swal.fire({title:'Hapus card?', icon:'warning', showCancelButton:true}).then(r=>{
    if(r.isConfirmed){
      $.post('api/list_delete.php', {id}, function(res){
        if(res.ok){ toast('success','Dihapus'); openBoard(CURRENT_BOARD_ID); }
        else toast('error', res.msg||'Gagal');
      },'json');
    }
  });
}

function initDrag(){
  document.querySelectorAll('.list-container').forEach(el => {
    new Sortable(el, {
      group: 'shared',
      animation: 150,
      onAdd: function(evt){
        const card = $(evt.item);
        const id = card.data('id');
        const targetSubBoard = $(evt.to).closest('.column').data('subboard-id');
        // If dragging into review, prompt review
        const columnName = $(evt.to).closest('.column').find('h6').text().trim().toLowerCase();
        if(columnName === 'review'){
          // Show quick review dialog
          Swal.fire({
            title: 'Hasil Review',
            input: 'select',
            inputOptions: { 'approved':'Approved', 'revisi':'Revisi' },
            inputPlaceholder: 'Pilih hasil',
            showCancelButton: true
          }).then(sel=>{
            if(sel.isConfirmed){
              $.post('api/review_update.php', {id, status: sel.value}, function(res){
                if(res.ok){
                  card.attr('data-review', sel.value);
                  // move ok
                  moveCard(id, targetSubBoard, evt, card);
                } else {
                  toast('error', res.msg || 'Gagal set review');
                  evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex]); // revert
                }
              },'json');
            } else {
              evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex]); // revert
            }
          });
        } else if (columnName === 'done'){
          // Require approved
          $.post('api/list_update.php', {id, sub_board_id: targetSubBoard, just_check: 1}, function(res){
            if(res.ok){
              moveCard(id, targetSubBoard, evt, card);
            } else {
              toast('error', res.msg || 'Belum boleh ke Done');
              evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex]); // revert
            }
          }, 'json');
        } else {
          moveCard(id, targetSubBoard, evt, card);
        }
      }
    });
  });
}

function moveCard(id, sub_board_id, evt, cardEl){
  $.post('api/list_update.php', {id, sub_board_id}, function(res){
    if(res.ok){
      // success
    } else {
      toast('error', res.msg || 'Gagal pindah'); 
      evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex]); // revert
    }
  }, 'json');
}

function pollOverdue(){
  if(!CURRENT_BOARD_ID) return;
  $.get('api/overdue_check.php', {board_id: CURRENT_BOARD_ID}, function(res){
    res.data.forEach(item => {
      if(!OVERDUE_SEEN.has(item.id)){
        OVERDUE_SEEN.add(item.id);
        playBeep();
        Swal.fire({
          icon:'warning',
          title: 'Deadline lewat!',
          html: `<b>${item.title}</b><br>Deadline: ${item.deadline}`,
          confirmButtonText: 'Tambah waktu'
        }).then(r=>{
          if(r.isConfirmed){
            Swal.fire({title:'Tambah menit', input:'number', inputAttributes:{min:1}, inputValue:30}).then(q=>{
              if(q.isConfirmed){
                $.post('api/deadline_update.php', {id:item.id, add_minutes:q.value||30}, function(r2){
                  if(r2.ok){ toast('success','Deadline diperpanjang'); openBoard(CURRENT_BOARD_ID); }
                },'json');
              }
            });
          }
        });
      }
    });
  });
}

setInterval(pollOverdue, 15000); // 15s

$(document).on('click', '#boardList .list-group-item', function(){
  const id = $(this).data('id');
  openBoard(id);
});

// On load, set UI according to role (will be injected server-side via PHP in home.php)
$(function(){
  // show/hide add board button for non-admins
  if (window.CURRENT_USER_ROLE && window.CURRENT_USER_ROLE !== 'admin') {
    $('#createBoardBtn').hide();
  }
});
