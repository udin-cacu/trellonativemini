<?php require __DIR__.'/config/auth.php'; ?>
<?php include __DIR__.'/partials/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Boards</h3>
</div>

<div class="row">
  <div class="col-auto sidebar p-0">
    <div class="p-3 d-flex justify-content-between align-items-center">
      <strong>Boards</strong>
    </div>
    <p>
      <div>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addBoardModal">
          + Board
        </button>

      </div>
      <button id="createBoardBtn" class="btn btn-sm btn-primary" onclick="showAddBoard()">+ Board</button>
    </p>
    <ul class="list-group list-group-flush" id="boardList"></ul>
  </div>
  <div class="col main">
    <div id="boardArea" class="p-3 text-muted">Pilih board di kiri...</div>
  </div>
</div>

<div class="modal fade" id="addBoardModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="addBoardForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Board</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="text" name="title" class="form-control" placeholder="Nama Board" required>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="saveBoardBtn">Simpan</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="addListModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="addListForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah List</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="text" name="title" class="form-control mb-2" placeholder="Judul List" required>
        <textarea name="description" class="form-control mb-2" placeholder="Deskripsi"></textarea>
        
        <label>Board</label>
        <select name="board_id" id="boardSelect" class="form-select mb-2"></select>

        <label>Sub Board</label>
        <select name="sub_board_id" id="subBoardSelect" class="form-select mb-2"></select>

        <label>Priority</label>
        <select name="priority" class="form-select mb-2">
          <option value="low">Low</option>
          <option value="medium" selected>Medium</option>
          <option value="high">High</option>
        </select>

        <label>Deadline</label>
        <input type="datetime-local" name="deadline" class="form-control mb-2">

        <label>Labels</label><br>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" name="labels[]" value="red"> <span class="badge bg-danger">Red</span>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" name="labels[]" value="green"> <span class="badge bg-success">Green</span>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" name="labels[]" value="blue"> <span class="badge bg-primary">Blue</span>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" name="labels[]" value="yellow"> <span class="badge bg-warning">Yellow</span>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" name="labels[]" value="purple"> <span class="badge bg-purple text-white">Purple</span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Simpan</button>
      </div>
    </form>
  </div>
</div>


<!-- Add/Edit Board Modal -->
<div class="modal fade" id="boardModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Board</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <label class="form-label">Judul</label>
        <input type="text" class="form-control mb-2" id="newBoardTitle" placeholder="Judul project">

        <div class="mb-2">
          <label class="form-label">Labels (multi)</label><br/>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="board_label_red">
            <label class="form-check-label" for="board_label_red">Red</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="board_label_green">
            <label class="form-check-label" for="board_label_green">Green</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="board_label_blue">
            <label class="form-check-label" for="board_label_blue">Blue</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="board_label_yellow">
            <label class="form-check-label" for="board_label_yellow">Yellow</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="board_label_purple">
            <label class="form-check-label" for="board_label_purple">Purple</label>
          </div>
        </div>
      </div> <!-- ✅ tutup modal-body -->

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-primary" onclick="saveBoard()">Simpan</button>
      </div>
    </div>
  </div>
</div>

<!-- Add/Edit Card Modal -->
<div class="modal fade" id="cardModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Card</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="cardId">
        <input type="hidden" id="cardSubBoardId">

        <div class="mb-2">
          <label class="form-label">Judul</label>
          <input type="text" id="cardTitle" class="form-control">
        </div>
        <div class="mb-2">
          <label class="form-label">Deskripsi</label>
          <textarea id="cardDesc" class="form-control" rows="3"></textarea>
        </div>
        <div class="row g-2 mb-2">
          <div class="col">
            <label class="form-label">Assignee</label>
            <input type="text" id="cardAssignee" class="form-control" placeholder="Nama orang">
          </div>
          <div class="col">
            <label class="form-label">Priority</label>
            <select id="cardPriority" class="form-select">
              <option value="low">Low</option>
              <option value="medium">Medium</option>
              <option value="high">High</option>
            </select>
          </div>
        </div>
        <div class="mb-2">
          <label class="form-label">Deadline</label>
          <input type="datetime-local" id="cardDeadline" class="form-control">
        </div>
        <div class="row g-2 mb-2">
          <div class="col">
            <label class="form-label">Review Status</label>
            <select id="cardReview" class="form-select">
              <option value="none">None</option>
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="revisi">Revisi</option>
            </select>
          </div>
          <div class="col">
            <label class="form-label">Review Notes</label>
            <input type="text" id="cardReviewNotes" class="form-control">
          </div>
        </div>
        <div class="mb-2">
          <label class="form-label">Labels (multi)</label><br/>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="card_label_red">
            <label class="form-check-label" for="card_label_red">Red</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="card_label_green">
            <label class="form-check-label" for="card_label_green">Green</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="card_label_blue">
            <label class="form-check-label" for="card_label_blue">Blue</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="card_label_yellow">
            <label class="form-check-label" for="card_label_yellow">Yellow</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="card_label_purple">
            <label class="form-check-label" for="card_label_purple">Purple</label>
          </div>
        </div>
      </div> <!-- ✅ tutup modal-body -->

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-primary" onclick="saveCard()">Simpan</button>
      </div>
    </div>
  </div>
</div>

<script>
  $(function(){ loadBoards(); });

// contoh fungsi untuk tampilkan modal add card
  function showAddCard(subBoardId) {
    $("#cardId").val("");
    $("#cardSubBoardId").val(subBoardId);
    $("#cardModal").modal("show");
  }


// Tambah Board
  /*$("#addBoardForm").on("submit", function(e){
    e.preventDefault();
    $.post("ajax/add_board.php", $(this).serialize(), function(res){
      let r = JSON.parse(res);
      if(r.status=="ok"){ location.reload(); }
      else { Swal.fire("Error", r.msg, "error"); }
    });
  });*/

// Tambah List
  $("#addListForm").on("submit", function(e) {
    e.preventDefault();

    $.ajax({
      url: "ajax/add_list.php",
      type: "POST",
      data: $(this).serialize(),
      success: function(res) {
        res = res.trim();
        if (res === "success") {
                // Tutup modal
          $("#addListModal").modal("hide");
          $("#addListForm")[0].reset();

                // Refresh halaman
          location.reload();
        } else {
          Swal.fire({
            icon: "error",
            title: "Gagal tambah list",
            text: res
          });
        }
      }
    });
  });


// Saat modal tambah list dibuka, load board & sub board
  $('#addListModal').on('show.bs.modal', function(){
    $.getJSON("ajax/get_boards.php", function(data){
      let $boardSel = $("#boardSelect").empty();
      data.forEach(b=>{
        $boardSel.append(`<option value="${b.id}">${b.title}</option>`);
      });
      $boardSel.trigger("change");
    });
  });

  $("#boardSelect").on("change", function(){
    let bid = $(this).val();
    $.getJSON("ajax/get_subboards.php",{board_id:bid}, function(data){
      let $subSel = $("#subBoardSelect").empty();
      data.forEach(s=>{
        $subSel.append(`<option value="${s.id}">${s.title}</option>`);
      });
    });
  });

  $("#addBoardForm").on("submit", function(e) {
    e.preventDefault(); // ⬅️ ini wajib, biar form nggak submit normal

    $.ajax({
      url: "ajax/add_board.php",
      type: "POST",
      data: $(this).serialize(),
      success: function(res) {
        if (res.trim() === "success") {
          $("#addBoardModal").modal("hide");
          $("#addBoardForm")[0].reset();

          Swal.fire({
            icon: "success",
            title: "Board berhasil ditambahkan!",
            showConfirmButton: false,
            timer: 1500
          });

                loadBoards(); // reload list board
              } else {
                Swal.fire({
                  icon: "error",
                  title: "Gagal tambah board",
                  text: res
                });
              }
            }
          });
  });

  $("#saveBoardBtn").on("click", function() {
    $("#addBoardForm").submit();
  });


</script>

<?php $_role = $_SESSION['role'] ?? 'user'; ?>
<script>window.CURRENT_USER_ROLE = '<?php echo $_role; ?>';</script>
<?php include __DIR__.'/partials/footer.php'; ?>

