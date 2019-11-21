<?php
/*
  Template name: About
*/

  $body = apply_filters('the_content', $post->post_content);
  $header_video = get_post_meta($post->ID, '_cmb2_header_video', true);
  $featured_image = get_the_post_thumbnail_url($post, 'banner');
  $stats = get_post_meta($post->ID, '_cmb2_about_stats', true);
  $platform = get_post_meta($post->ID, '_cmb2_about_platform', true);
?>

<div class="container">
  <?php get_template_part('templates/page', 'header'); ?>

  <?php if (!empty($header_video)): ?>
    <div class="header-video container">
      <div id="header-video" class="video is-hidden" data-url="<?=  $header_video ?>"></div>
    </div>
  <?php else: ?>
    <div class="header-image container">
      <div class="image" style="background-image:url('<?= $featured_image ?>');"></div>
    </div>
  <?php endif ?>

  <div class="page-content user-content">
    <?= $body ?>
  </div>

  <div id="stats" class="stats-section">
    <?php if (!empty($stats)): ?>
      <ul class="stats animate-in-series">
        <?php foreach ($stats as $stat): ?>
          <li class="stat animation-item">
            <div class="-inner">
              <h5><?= $stat['figure'] ?></h5>
              <p><?= $stat['description'] ?></p>
            </div>
          </li>
        <?php endforeach ?>
      </ul>
    <?php endif ?>
  </div>

  <p class="portfolio-link"><a class="arrow-link" href="/portfolio">View Our Portfolio <svg class="icon icon-arrow-sm" aria-hidden="true" role="presentation"><use xlink:href="#icon-arrow-sm"/></svg></a></p>
</div>

<div id="platform" class="platform-section">
  <div class="container">
    <h2>Fully-Integrated<br> Real Estate Platform</h2>

    <?php if (!empty($platform)): ?>
      <ul class="platform">
        <?php foreach ($platform as $platform): ?>
          <li class="platform-item">
            <div class="-inner">
              <?php if (!empty($platform['icon'])): ?>
                <div class="icon">
                  <img src="<?= $platform['icon'] ?>">
                </div>
              <?php endif ?>
              <h5><?= $platform['title'] ?></h5>
              <p><?= $platform['description'] ?></p>
            </div>
          </li>
        <?php endforeach ?>
      </ul>
    <?php endif ?>

    <p class="team-link"><a class="arrow-link" href="/team">Meet Our Team <svg class="icon icon-arrow-sm" aria-hidden="true" role="presentation"><use xlink:href="#icon-arrow-sm"/></svg></a></p>
  </div>
</div>