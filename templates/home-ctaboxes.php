<?php
// Call global variable that stores options
global $trainhsa;

// Grab our active blocks from the database
$ctaBlocks = $trainhsa['opt-cta-layout']['enabled'];
// Get rid of the placebo item. We don't want that to mess up our stuff
unset($ctaBlocks['placebo']);
// Count the number of blocks we'll have
$ctaBlocksCount = count($ctaBlocks);

/*
if($ctaBlocksCount == 7) {
	$cols = 'col-md-3 col-sm-6';
} elseif( ($ctaBlocksCount == 6) || ($ctaBlocksCount == 5) ) {
	$cols = 'col-md-4 col-sm-6';
} else {
	$cols = 'col-md-3 col-sm-6';
}*/
$cols = 'col-md-3 col-sm-6';

$iconArray = array(
		'training'		=> 'university',
		'leagues'		=> 'users',
		'camps'			=> 'life-ring',
		'tournaments'	=> 'trophy',
		'homeschool'	=> 'home',
		'afterschool'	=> 'taxi',
		'travelteams'	=> 'rocket',
	);

// Grab the free trial shortcode. We'll need it if the user has enabled the option
$freeTrialShortcode = $trainhsa['opt-cta-signup-form'];
$i = 1;
// Loop through our boxes
foreach($ctaBlocks as $block => $blockTitle) { ?>
	<div class="<?php echo $cols; ?>">
		<div class="box">							
			<div class="icon">
				<div class="image"><i class="fa fa-<?php echo $iconArray[$block]; ?>"></i></div>
				<div class="info">
					<h3 class="title"><?php echo $blockTitle; ?></h3>
					<p><?php echo $trainhsa['opt-cta-'.$block.'-text']; ?></p>
					
					<div class="more">
						<a href="<?php echo get_permalink( $trainhsa['opt-cta-'.$block.'-link']); ?>" class="btn btn-link">
							<?php echo $trainhsa['opt-cta-'.$block.'-link-text']; ?>
						</a>
						<?php if( ($block == 'training') && ( intval($trainhsa['opt-cta-training-trial-boolean']) == 1) ) { ?>
							<div class="clearfix"></div>
							<h5>or</h5>
							<a href="#" id="trialModalTrigger" class="btn btn-link" data-toggle="modal" data-target="#trialModal">
									<?php echo $trainhsa['opt-cta-training-trial-text']; ?>
							</a>
						<?php }?>
					</div>
				</div>
			</div>
			<div class="space"></div>
		</div> 
	</div>
	<?php
	if( $i % 2 == 0 ) {
		echo('<div class="clearfix visible-sm"></div>');
	}
	$i++ ?>
<?php } ?>