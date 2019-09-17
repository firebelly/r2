<header id="site-header" class="site-header" role="banner">
  <div class="container">
    <h1 class="site-logo"><a href="<?= esc_url(home_url('/')); ?>"><span class="visually-hidden"><?php bloginfo('name'); ?></span><svg aria-hidden="true" role="presentation"><use xlink:href="#r2"/></svg></a></h1>
    <nav id="site-nav" class="site-nav" role="navigation">
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']);
      endif;
      ?>
    </nav>
  </div>
</header>
