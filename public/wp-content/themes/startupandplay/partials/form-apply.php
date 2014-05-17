<form class="form-horizontal" role="form">
  <input type="hidden" value="form-apply">
  <div class="form-group">
    <label for="apply_venture_name" class="col-sm-2 control-label">Venture</label>
    <div class="col-sm-10">
      <input type="name" class="form-control" id="apply_venture_name" placeholder="Venture Name" required>
    </div>
  </div>
  <div class="form-group">
    <label for="apply_name" class="col-sm-2 control-label">Name</label>
    <div class="col-sm-10">
      <input type="name" class="form-control" id="apply_name" placeholder="Name" required>
    </div>
  </div>
  <div class="form-group">
    <label for="apply_email" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-10">
      <input type="email" class="form-control" id="apply_email" placeholder="Email" required>
    </div>
  </div>
  <div class="form-group">
    <label for="apply_pitch" class="col-sm-2 control-label">Pitch</label>
    <div class="col-sm-10">
      <textarea class="form-control" rows="6" id="apply_pich" placeholder="Elevator Pitch" required></textarea>
    </div>
  </div>
  <div class="col-sm-offset-2 col-sm-10">
    <button type="submit" class="btn btn-default">Apply</button>
  </div>
</form>