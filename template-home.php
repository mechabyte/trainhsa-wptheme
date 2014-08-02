<?php
/*
Template Name: Homepage Template
*/
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'page'); ?>
<?php endwhile; ?>

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
</div>
<div class='col-md-8'>
	      <div class="carousel slide" data-ride="carousel" id="quote-carousel">
	        <!-- Bottom Carousel Indicators -->
	        <ol class="carousel-indicators">
	          <li data-target="#quote-carousel" data-slide-to="0" class="active"></li>
	          <li data-target="#quote-carousel" data-slide-to="1"></li>
	          <li data-target="#quote-carousel" data-slide-to="2"></li>
	        </ol>
	        
	        <!-- Carousel Slides / Quotes -->
	        <div class="carousel-inner">
	        
	          <!-- Quote 1 -->
	          <div class="item active">
	            <blockquote>
	              <div class="row">
	                <div class="col-sm-3 text-center">
	                  <img class="img-circle" src="https://s3.amazonaws.com/uifaces/faces/twitter/kolage/128.jpg" style="width: 100px;height:100px;">
	                </div>
	                <div class="col-sm-9">
	                  <p>Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit!</p>
	                  <small>Someone famous</small>
	                </div>
	              </div>
	            </blockquote>
	          </div>
	          <!-- Quote 2 -->
	          <div class="item">
	            <blockquote>
	              <div class="row">
	                <div class="col-sm-3 text-center">
	                  <img class="img-circle" src="https://s3.amazonaws.com/uifaces/faces/twitter/mijustin/128.jpg" style="width: 100px;height:100px;">
	                </div>
	                <div class="col-sm-9">
	                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam auctor nec lacus ut tempor. Mauris.</p>
	                  <small>Someone famous</small>
	                </div>
	              </div>
	            </blockquote>
	          </div>
	          <!-- Quote 3 -->
	          <div class="item">
	            <blockquote>
	              <div class="row">
	                <div class="col-sm-3 text-center">
	                  <img class="img-circle" src="https://s3.amazonaws.com/uifaces/faces/twitter/keizgoesboom/128.jpg" style="width: 100px;height:100px;">
	                </div>
	                <div class="col-sm-9">
	                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut rutrum elit in arcu blandit, eget pretium nisl accumsan. Sed ultricies commodo tortor, eu pretium mauris.</p>
	                  <small>Someone famous</small>
	                </div>
	              </div>
	            </blockquote>
	          </div>
	        </div>
	        
	        <!-- Carousel Buttons Next/Prev -->
	        <a data-slide="prev" href="#quote-carousel" class="left carousel-control"><i class="fa fa-chevron-left"></i></a>
	        <a data-slide="next" href="#quote-carousel" class="right carousel-control"><i class="fa fa-chevron-right"></i></a>
		</div>
	</div>