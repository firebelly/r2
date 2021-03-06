<?php
  use Roots\Sage\Titles;
  $header_video = get_post_meta($post->ID, '_cmb2_header_video', true);
  $header_images = get_post_meta($post->ID, '_cmb2_header_images', true);
  $title = $post->post_title;
  $title_first = get_post_meta($post->ID, '_cmb2_title_large', true);
  $title_second = get_post_meta($post->ID, '_cmb2_title_small', true);
  $locality = get_post_meta($post->ID, '_cmb2_locality', true);
  $description = get_post_meta($post->ID, '_cmb2_description', true);
  $square_footage = get_post_meta($post->ID, '_cmb2_square_footage', true);
  $callout_headline = get_post_meta($post->ID, '_cmb2_callout_headline', true);
  $callout_copy = get_post_meta($post->ID, '_cmb2_callout_copy', true);
  $downloads = get_post_meta($post->ID, '_cmb2_downloads', true);
  $project_images = get_post_meta($post->ID, '_cmb2_project_images', true);
  $featured_image = get_the_post_thumbnail_url($post, 'banner');
  $project_comparison_images = get_post_meta($post->ID, '_cmb2_project_comparison_images', true);
  $project_video = get_post_meta($post->ID, '_cmb2_project_video', true);
?>

<?php if (!empty($header_video)): ?>
  <div class="header-video container">
    <div class="video">
      <div id="loading-spinner"><span></span><span></span><span></span></div>
      <iframe src="https://player.vimeo.com/video/<?= $header_video ?>?background=1&autoplay=1&loop=1&byline=0&title=0"
                 frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    </div>
  </div>
<?php elseif (!empty($header_images)): ?>
  <?php if (sizeof($header_images) > 1): ?>
    <div class="header-carousel is-hidden container">
      <?php foreach ($header_images as $attachment_id => $attachment_url): ?>
        <?php $carousel_image = wp_get_attachment_image_src( $attachment_id, 'banner')[0]; ?>
        <div class="image" style="background-image:url('<?= $carousel_image ?>');"></div>
      <?php endforeach ?>
    </div>
  <?php else: ?>
    <div class="header-image container">
      <?php foreach ($header_images as $attachment_id => $attachment_url): ?>
        <?php $carousel_image = wp_get_attachment_image_src( $attachment_id, 'banner')[0]; ?>
        <div class="image" style="background-image:url('<?= $carousel_image ?>');"></div>
      <?php endforeach ?>
    </div>
  <?php endif ?>
<?php elseif (!empty($featured_image)): ?>
  <div class="header-image container">
    <div class="image" style="background-image:url('<?= $featured_image ?>');"></div>
  </div>
<?php endif ?>

<div class="project-info">
  <div class="top-row grid">
    <div class="col-md-1-2">
      <div class="container">
        <h1 class="project-title">
          <?php if (!empty($title_first)): ?>
            <span class="first"><?= $title_first ?></span>
            <?php if (!empty($title_second)): ?>
              <span class="second"><?= $title_second ?></span>
            <?php endif ?>
          <?php else: ?>
            <?= $title ?>
          <?php endif ?>
        </h1>
        <?php if (!empty($locality)): ?>
          <p class="locality"><?= $locality ?></p>
        <?php endif ?>
      </div>
    </div>
    <div class="col-md-1-2">
      <div class="container">
        <?php if (!empty($description)): ?>
          <div class="description user-content">
            <?= apply_filters('the_content', $description) ?>
          </div>
        <?php endif ?>

        <?php if (!empty($square_footage)): ?>
          <h5 class="square-footage"><?= $square_footage ?></h5>
        <?php endif ?>

        <?php if (!empty($callout_copy)): ?>
          <div class="callout">
            <?php if (!empty($callout_headline)): ?>
              <h5><?= $callout_headline ?></h5>
            <?php endif ?>
            <?php if (!empty($callout_copy)): ?>
              <div class="callout-copy user-content">
                <?= apply_filters('the_content', $callout_copy) ?>
              </div>
            <?php endif ?>
          </div>
        <?php endif ?>
      </div>
    </div>
  </div>

  <?php if (!empty($downloads)): ?>
    <div class="bottom-row grid align-right">
      <div class="downloads col-md-1-2">
        <div class="container">
          <h3>Download</h3>
          <ul>
            <?php foreach ($downloads as $download): ?>
              <li><a href="<?= $download['file'] ?>" target="_blank" data-no-swup><?= $download['label'] ?></a></li>
            <?php endforeach ?>
          </ul>
        </div>
      </div>
    </div>
  <?php endif ?>
</div>

<?php if (!empty($project_images)): ?>
  <div class="project-images">
    <div class="-inner container">
      <?php foreach ($project_images as $image): ?>
        <?php
          $size = $image['size'];
          $crop = 'project_' . $size;
          if (!empty($image['bw'])) {
            $file = Firebelly\Media\get_treated_image('image', ['bw' => true, 'thumb_id' => $image['file_id'], 'size' => $crop, 'output' => false]);
          } else {
            $file = wp_get_attachment_image_src($image['file_id'], $crop)[0];
          }
          $image_alt = get_post_meta($image['file_id'], '_wp_attachment_image_alt', TRUE);
          $caption = wp_get_attachment_caption($image['file_id']);
        ?>
        <figure class="animate-in <?= $image['size'] ?>">
          <img src="<?= $file ?>" alt="<?= !empty($image_alt) ? $image_alt : get_the_title($image['file_id']); ?>">
          <?php if (!empty($caption)): ?>
            <figcaption>
              <p><?= $caption ?></p>
            </figcaption>
          <?php endif ?>
        </figure>
      <?php endforeach ?>
    </div>
  </div>
<?php endif ?>

<?php if (!empty($project_comparison_images)): ?>
  <div class="comparison-images">
    <div class="-inner container">
      <?php foreach ($project_comparison_images as $image): ?>
        <?php
          $beforeImage = wp_get_attachment_image_src($image['before_id'], 'project_medium')[0];
          $afterImage = wp_get_attachment_image_src($image['after_id'], 'project_medium')[0];
        ?>
        <div class="comparison animate-in">
          <div class="cocoen">
            <img src="<?= $beforeImage ?>" alt="">
            <img src="<?= $afterImage ?>" alt="">
          </div>
          <div class="comparison-labels">
            <h6 class="before">Before</h6>
            <h6 class="after">After</h6>
          </div>
        </div>
      <?php endforeach ?>
    </div>
  </div>
<?php endif ?>

<?php if (!empty($project_video)): ?>
  <?php
    $video_thumb = get_post_meta($post->ID, '_cmb2_video_thumbnail', true);
  ?>
  <div class="project-video-section container">
    <div class="project-video-container animate-in">
      <div class="video-thumb" style="background-image: url('<?= $video_thumb ?>');">
        <svg class="project-video-play" aria-hidden="true" role="presentation"><use xlink:href="#icon-play"/></svg>
      </div>
      <div id="project-video" class="project-video" data-url="<?=  $project_video ?>"></div>
    </div>
  </div>
<?php endif ?>

<?php get_template_part('templates/project-nav'); ?>