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
