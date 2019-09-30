<?php
/*
  Template name: Homepage
*/
use Roots\Sage\Titles;

$animation_text = get_post_meta($post->ID, '_cmb2_animation_text', true);
$animation_text_words = explode(' ', $animation_text);
$banner_video = get_post_meta($post->ID, '_cmb2_banner_video', true);
?>

<div class="homepage-banner">
  <?php if (!empty($banner_video)): ?>
    <div class="background-video">
      <iframe src="https://player.vimeo.com/video/<?= $banner_video ?>?background=1&autoplay=1&loop=1&byline=0&title=0"
                 frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    </div>
  <?php endif ?>
  <div class="intro-animation">
    <div class="container">
      <div class="intro-text">
        <?php if (!empty($animation_text)): ?>
          <p class="container">
            <?php foreach ($animation_text_words as $word): ?>
              <span><?= $word ?></span>
            <?php endforeach ?>
          </p>
        <?php else: ?>
          <p class="container"><span>In</span> <span>Relentless</span> <span>Pursuit</span> <span>of</span> <span>Opportunity.</span></p>
        <?php endif ?>
      </div>
      <svg class="r2-logo" aria-hidden="true" role="presentation"><use xlink:href="#r2"/></svg>
    </div>
  </div>
</div>