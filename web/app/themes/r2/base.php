<?php
use Roots\Sage\Setup;
use Roots\Sage\Wrapper;
$breadcrumbTitle = ($post) ? $post->post_title : '404 Page Not Found';
$breadcrumb = (is_front_page()) ? 'R2 Companies' :  $breadcrumbTitle;
?>
<!DOCTYPE html>
<!--[if IE 9 ]> <html class="no-js ie9 lt-ie10" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
  <?php get_template_part('templates/head'); ?>
  <body <?php body_class(); ?>>
    <div id="breakpoint-indicator"></div>
    <div class="svg-defs hidden"><?php include('dist/svgs-defs.svg'); ?></div>

    <div id="swup">
      <?php
        do_action('get_header');
        get_template_part('templates/header');
      ?>
      <div class="site-wrap transition-fade" role="document">
        <main class="site-main" role="main">
          <div class="breadcrumbs">/ <?= $breadcrumb ?></div>
          <?php include Wrapper\template_path(); ?>
        </main><!-- /.main -->
      </div><!-- /.site-wrap -->
    </div>

    <?php
      do_action('get_footer');
      get_template_part('templates/footer');
      wp_footer();
    ?>
  </body>
</html>
