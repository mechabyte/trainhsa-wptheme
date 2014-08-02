<header class="banner navbar navbar-default navbar-static-top" role="banner">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
    </div>

    <nav class="collapse navbar-collapse" role="navigation">
      <?php
        if (has_nav_menu('primary_navigation')) :
          wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'nav navbar-nav'));
        endif;
      ?>
      <div class="nav navbar-nav navbar-right">
        <form action="<?php bloginfo('siteurl'); ?>" id="searchform" method="get" class="navbar-form" role="search">
        <div class="input-group">
            <input type="text" id="s" name="s" class="form-control" value="<?php echo get_search_query(); ?>" placeholder="Search" name="q">
            <div class="input-group-btn">
                <button class="btn btn-dark" type="submit"><i class="fa fa-fw fa-search"></i></button>
            </div>
        </div>
        </form>
      </div>
    </nav>
  </div>
</header>
