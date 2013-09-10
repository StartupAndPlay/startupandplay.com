            </div><!-- closes container-screen-content -->
        </div><!-- closes container-screen -->
    </div><!-- closes container -->
<footer>
<?php if ( is_page('New Post') ) {
    echo '<script type="text/javascript" src="/wp-content/themes/startupandplay/js/grande.js"></script>';
} ?>
<?php if ( is_page('New Post') ) {
    echo '<script type="text/javascript"> grande.bind(); </script>';
} ?>
<?php wp_footer(); ?>
<!-- Google Analytics UA-XXXXXXXX-X -->
<!-- <script type="text/javascript">
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-XXXXXXXX-X']);
_gaq.push(['_setDomainName', 'startupandplay.com']);
_gaq.push(['_trackPageview']);

(function() {
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
</script> -->
</footer>
</body>
</html>