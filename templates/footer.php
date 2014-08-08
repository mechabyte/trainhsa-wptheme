<?php
global $trainhsa;
$socialBase = array(
	'facebook'	=> '',
	'twitter'	=> 'https://twitter.com/',
	'instagram'	=> 'https://instagram.com/',
	'youtube'	=> 'https://youtube.com/'
	);
?>
<div id="footer-social">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 text-center">
				<?php foreach($socialBase as $key => $base) {
					$networkID = $trainhsa['opt-social-'.$key];
					if($networkID != "") { ?>
						<a href="<?php echo $base; echo $networkID; ?>" target="_blank"><i id="social-<?php echo $key; ?>" class="fa fa-<?php echo $key; ?> fa-3x"></i></a>
					<?php }
				} ?>
			</div>
		</div>
	</div>
</div>
<footer class="content-info" role="contentinfo">
  <div class="container">
  	<div class="row">
  	</div>
    <?php dynamic_sidebar('sidebar-footer'); ?>
  </div>
</footer>

<?php wp_footer(); ?>
<?php if($trainhsa['opt-custom-css'] != "") { ?>
	<style>
	<?php echo $trainhsa['opt-custom-css']; ?>
	</style>
<?php } ?>
<?php if($trainhsa['opt-custom-js'] != "") { ?>
	<script>
	<?php echo $trainhsa['opt-custom-js']; ?>
	</script>
<?php } ?>
<?php if($trainhsa['opt-analytics'] != "") { ?>
	<script>
        (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
        function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
        e=o.createElement(i);r=o.getElementsByTagName(i)[0];
        e.src='//www.google-analytics.com/analytics.js';
        r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
        ga('create',<?php echo $trainhsa['opt-analytics']; ?>,'auto');ga('send','pageview');
    </script>
<?php } ?>
