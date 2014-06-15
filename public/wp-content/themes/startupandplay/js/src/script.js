jQuery(document).ready(function($) {

  $('input, textarea').placeholder();

  $(function() {
    var iframes = document.getElementsByTagName('iframe');
    
    for (var i = 0; i < iframes.length; i++) {
      var iframe = iframes[i];
      var players = /www.youtube.com|player.vimeo.com/;
      if(iframe.src.search(players) !== -1) {
        var videoRatio = (iframe.height / iframe.width) * 100;

        iframe.style.position = 'absolute';
        iframe.style.top = '0';
        iframe.style.left = '0';
        iframe.width = '100%';
        iframe.height = '100%';

        var div = document.createElement('div');
        div.className = 'video-wrap';
        div.style.width = '100%';
        div.style.position = 'relative';
        div.style.paddingTop = videoRatio + '%';

        var parentNode = iframe.parentNode;
        parentNode.insertBefore(div, iframe);
        div.appendChild(iframe);
      }
    }
  });

  var masthead = $('.blur');
  var mastheadHeight = masthead.outerHeight();

  $(document).scroll(function(e){
    var opacity = 1-((200 - window.scrollY) / 200);
    if(opacity >= 0){
      masthead.css('opacity', opacity);
    }
  });

  var mailgunURL, mailgun, response;

  if(!window.location.origin) {

    window.location.origin = window.location.protocol + "//" + window.location.hostname;

  }

  mailgunURL = window.location.origin + '/ajax/mailgun.php';

  $('#mailgun').on('submit',function(e) {

    e.preventDefault();

      $.ajax({
          type     : 'POST',
          cache    : false,
          url      : mailgunURL,
          data     : $(this).serialize(),
          success  : function(data) {
            responseSuccess(data);
            console.log(data);
          },
          error  : function(data) {
            console.log('Silent failure.');
          }
      });

    return false;

  });

  function responseSuccess(data) {

    mailgun = $('#mailgun');
    response = $('#response');

    data = JSON.parse(data);

    if(data.status === 'success') {
      response.text('Submission sent succesfully.');
    } else {
      response.text('Submission failed to send, please email us directly at submissions[at]startupandplay.com');
    }

    mailgun.fadeOut(800, function() {
      response.fadeIn(800);
    });


  }





});
