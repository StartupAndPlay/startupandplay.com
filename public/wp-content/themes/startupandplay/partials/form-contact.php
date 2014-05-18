<form class="form-horizontal" role="form" id="mailgun" method="POST">
  <input type="hidden" name="form" value="form-contact">
  <input type="hidden" name="subject" value="New Contact Request">
  <input type="hidden" type="email" name="email_2" value="">
  <div class="form-group">
    <label for="contact_name" class="col-sm-2 control-label">Name</label>
    <div class="col-sm-10">
      <input type="name" name="contact_name" class="form-control" id="contact_name" placeholder="Name" required>
    </div>
  </div>
  <div class="form-group">
    <label for="contact_email" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-10">
      <input type="email" name="contact_email" class="form-control" id="contact_email" placeholder="Email" required>
    </div>
  </div>
  <div class="form-group">
    <label for="contact_message" class="col-sm-2 control-label">Message</label>
    <div class="col-sm-10">
      <textarea class="form-control" name="contact_message" rows="6" id="contact_message" placeholder="Message" required></textarea>
    </div>
  </div>
  <div class="col-sm-offset-2 col-sm-10">
    <button type="submit" class="btn btn-default">Send</button>
  </div>
</form>

<div id="response"></div>