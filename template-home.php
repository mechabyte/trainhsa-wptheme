<?php
/*
Template Name: Homepage Template
*/
global $trainhsa;

$ctaBlocks = $trainhsa['opt-cta-layout']['enabled'];
unset($ctaBlocks['placebo']);
$ctaBlocksCount = count($ctaBlocks);

$freeTrialShortcode = $trainhsa['opt-cta-signup-form'];
?>

<?php /*
</main><!-- .main .col-sm-12 -->
</div><!-- .content .row -->
</div><!-- .wrap .container -->
*/ ?>
	<!-- Ajax Signup Alert -->
	<div class="row">
		<div id="ajaxSignupAlert" class="col-xs-12 alert alert-success fade in" role="alert" style="display:none;margin-top:2em;">
			<?php echo $trainhsa['opt-cta-signup-form-alert']; ?>
		</div>
	</div>
	<!-- CTA Boxes -->
	<div class="row ctaboxes">
		<?php get_template_part('templates/home-ctaboxes'); ?>
	</div>

	</div><!-- .main .col-sm-12 -->
	</div><!-- .content .row -->
	</div><!-- .wrap .container -->

<div id="homeWidgets">
	<div class="main col-sm-12">
		<div class="content row">
			<div class="wrap container">
				<div class="row">
					<!-- Widget Area -->
					<?php dynamic_sidebar('sidebar-frontpage'); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="homeQuotes">
	<div class="main col-sm-12">
		<div class="content row compliment-color">
			<div class="wrap container">
				<div class="row">
					<!-- Quote Slider -->
					<?php get_template_part('templates/home-quotes'); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<!--
<?php while (have_posts()) : the_post(); ?>
  <?php //get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'page'); ?>
<?php endwhile; ?>
-->
<!-- Modal -->
<div class="modal fade" id="trialModal" tabindex="-1" role="dialog" aria-labelledby="trialModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Training Free Trial</h4>
      </div>
      <div class="modal-body">
        <?php echo do_shortcode( $freeTrialShortcode ); ?>
      </div>
    </div>
  </div>
</div>
<div class="main col-sm-12" style="display:none;">
<?php /*
<!--
<div class="col-md-4">
<div class="list-group social-box">
        <a href="http://www.jquery2dotnet.com" class="list-group-item facebook-like">
            <h3 class="pull-right">
                <i class="fa fa-facebook-square"></i>
            </h3>
            <h4 class="list-group-item-heading count">
                <?php echo number_format( mechabyteSocialStats::facebook(117978191570403) ); ?></h4>
            <p class="list-group-item-text">
                Like Us on Facebook</p>
        </a><a href="http://www.jquery2dotnet.com" class="list-group-item twitter">
            <h3 class="pull-right">
                <i class="fa fa-twitter-square"></i>
            </h3>
            <h4 class="list-group-item-heading count">
                344</h4>
            <p class="list-group-item-text">
                Follow Us on Twitter</p>
        </a>
        <a href="http://www.jquery2dotnet.com" class="list-group-item youtube">
                        <h3 class="pull-right">
                            <i class="fa fa-youtube-play"></i>
                        </h3>
                        <h4 class="list-group-item-heading count">
                            <?php echo ( mechabyteSocialStats::youtube('techteenager') ); ?></h4>
                        <p class="list-group-item-text">
                            Subscribe on YouTube</p>
                    </a>
    </div>
</div>-->
?>