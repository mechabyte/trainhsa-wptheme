<?php
	$quote_args = array(
		'posts_per_page'   => 5,
		'orderby'          => 'rand',
		'order'            => 'DESC',
		'post_type'        => 'testimonial',
		'post_status'      => 'publish',
		'suppress_filters' => true );
	$quotes = get_posts($quote_args);
	$quoteCount = count($quotes);
	?>

<div class='col-sm-12'>
	<div class="carousel slide" data-ride="carousel" id="quote-carousel">
        <ol class="carousel-indicators">
        	<?php for($i=0;$i<$quoteCount;$i++) { ?>
        		<li data-target="#quote-carousel" data-slide-to="<?php echo $i; ?>"<?php if($i == 0) { echo(' class="active"'); } ?>></li>
        	<?php } ?>
        </ol>

        <div class="carousel-inner">
			<?php
			$i = 0;
			foreach($quotes as $quote) {
				setup_postdata( $quote );
				$quoteContent = wp_strip_all_tags( get_the_content() );
				$author = wp_strip_all_tags( get_post_meta( $quote->ID, '_ikcf_client', true ) );
				$authorPosition = wp_strip_all_tags( get_post_meta( $quote->ID, '_ikcf_position', true ) );?>
				<div class="item<?php if($i==0) { echo(' active'); } ?>">
					<blockquote>
						<div class="row">
							<div class="col-xs-10 col-xs-offset-1">
								<p><?php echo $quoteContent; ?></p>
								<small><?php echo $author; ?><?php if($authorPosition != "") { echo (" - ".$authorPosition); } ?></small>
							</div>
						</div>
					</blockquote>
				</div>
				<?php
				$i++;
			}
			wp_reset_postdata(); ?>
		</div>
	        
	        <!-- Carousel Buttons Next/Prev -->
		<a data-slide="prev" href="#quote-carousel" class="left carousel-control"><i class="fa fa-chevron-left"></i></a>
		<a data-slide="next" href="#quote-carousel" class="right carousel-control"><i class="fa fa-chevron-right"></i></a>
	</div>
</div>
<!--<div class="col-sm-12 text-center">
	<a href="<?php echo $trainhsa['opt-quotes-link']; ?>" class="testimonialsLink btn btn-compliment">More Testimonials</a>
</div>
-->