<?php

// Get global options from db
global $trainhsa;
// Grab slides we'll be using
$slides = $trainhsa['opt-slides']; 
// Count slides for comparison when adding active classes
$slideCount = count($slides);

?>
<!-- Init Carousel -->
<div id="home-carousel" class="carousel slide home-carousel" data-ride="carousel">
	<!-- Carousel Indicators -->
	<ol class="carousel-indicators hidden-xs hidden-sm">
        <?php foreach( $slides as $slide ) { ?>
        	<li data-target="#home-carousel" data-slide-to="<?php echo $slide['sort']; ?>" <?php if(intval($slide['sort']) == 0) { echo('class="active"'); } ?>></li>
        <?php } ?>
	</ol>
	<!-- Carousel Content -->
	<div class="carousel-inner">
		<?php
		foreach( $slides as $slide ) {
			$isVideo = preg_match("#^https?://(?:www\.)?youtube.com#", $slide['url']); ?>
			<div class="item <?php if($slide['sort'] == "0") { echo('active'); } ?> <?php if($isVideo) { echo('hasVideo'); } else { echo('noVideo'); } ?>">
                <img class="img-responsive img-full" src="<?php echo wp_get_attachment_image_src( $slide['attachment_id'], 'slider' )[0]; ?>" />
                
                <div class="carousel-caption">
                	<?php if($isVideo) { ?>
                		<div class="row">
                			<div class="col-xs-6 col-xs-offset-3 col-md-offset-0 col-md-6">
                				<?php echo do_shortcode('[fve]'.$slide['url'].'[/fve]'); ?>
                			</div>
                			<div class="hidden-xs hidden-sm col-md-6 caption-content">
                				<h1 class="carousel-caption-header"><?php echo $slide['title']; ?></h1>
                    			<p class="carousel-caption-text hidden-sm hidden-xs"><?php echo $slide['description']; ?></p>
                			</div>
                		</div>
                	<?php } else { ?>
                		<div class="row">
                			<div class="col-lg-8 col-lg-offset-2 caption-content">
                				<h1 class="carousel-caption-header"><?php echo $slide['title']; ?></h1>
                    			<p class="carousel-caption-text hidden-sm hidden-xs"><?php echo $slide['description']; ?></p>
                    			<?php if( $slide['url'] != "" ) { ?><a href="<?php echo $slide['url']; ?>" class="btn btn-primary btn-xs">Learn More</a><?php } ?>
                    		</div>
                		</div>

                	<?php } ?>
                </div>
            </div>
		<?php } ?>
	</div>
	<!-- Carousel Controls -->
	<a class="left carousel-control" href="#home-carousel" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left fa fa-chevron-left fa-2x"></span>
	</a>
	<a class="right carousel-control" href="#home-carousel" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right fa fa-chevron-right fa-2x"></span>
	</a>

</div>