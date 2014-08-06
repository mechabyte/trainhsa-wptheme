<?php

// Get global options from db
global $trainhsa;
// Grab slides we'll be using
$slides = $trainhsa['opt-slides']; 
// Count slides for comparison when adding active classes
$slideCount = count($slides);

?>
<!-- Init Carousel -->
<div id="transition-timer-carousel" class="carousel slide transition-timer-carousel" data-ride="carousel">
	<!-- Carousel Indicators -->
	<ol class="carousel-indicators">
        <?php foreach( $slides as $slide ) { ?>
        	<li data-target="#transition-timer-carousel" data-slide-to="<?php echo $slide['sort']; ?>" <?php if(intval($slide['sort']) == 0) { echo('class="active"'); } ?>></li>
        <?php } ?>
	</ol>
	<!-- Carousel Content -->
	<div class="carousel-inner">
		<?php foreach( $slides as $slide ) { ?>
			<div class="item<?php if(intval($slide['sort']) == 0) { echo(' active'); } ?>">
                <img class="img-responsive img-full" src="<?php echo wp_get_attachment_image_src( $slide['attachment_id'], 'slider' )[0]; ?> " />
                <div class="carousel-caption">
                    <h1 class="carousel-caption-header"><?php echo $slide['title']; ?></h1>
                    <p class="carousel-caption-text hidden-sm hidden-xs"><?php echo $slide['description']; ?></p>
                </div>
            </div>
		<? } ?>
	</div>
	<!-- Carousel Controls -->
	<a class="left carousel-control hidden-xs" href="#transition-timer-carousel" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left fa fa-chevron-left fa-2x"></span>
	</a>
	<a class="right carousel-control hidden-xs" href="#transition-timer-carousel" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right fa fa-chevron-right fa-2x"></span>
	</a>

</div>