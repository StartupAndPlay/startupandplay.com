<form class="form-horizontal" role="form" id="mailgun" method="POST">
  <input type="hidden" name="form" value="form-apply">
  <input type="hidden" name="subject" value="New Showcase Application">
  <input type="hidden" type="email" name="email_2" value="">
  <div class="form-group">
    <label for="apply_venture_name" class="col-sm-2 control-label">Venture</label>
    <div class="col-sm-10">
      <input type="name" name="apply_venture_name" class="form-control" id="apply_venture_name" placeholder="Venture Name" required>
    </div>
  </div>
  <div class="form-group">
    <label for="apply_name" class="col-sm-2 control-label">Name</label>
    <div class="col-sm-10">
      <input type="name" name="apply_name" class="form-control" id="apply_name" placeholder="Name" required>
    </div>
  </div>
  <div class="form-group">
    <label for="apply_email" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-10">
      <input type="email" name="apply_email" class="form-control" id="apply_email" placeholder="Email" required>
    </div>
  </div>
  <div class="form-group">
    <label for="apply_pitch" class="col-sm-2 control-label">Pitch</label>
    <div class="col-sm-10">
      <textarea name="apply_pitch" class="form-control" rows="6" id="apply_pitch" placeholder="Elevator Pitch" required></textarea>
    </div>
  </div>
  <div class="col-sm-offset-2 col-sm-10">
    <button type="submit" class="btn btn-default">Apply</button>
  </div>
</form>

<div id="response"></div>