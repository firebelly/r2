<?php
  use Roots\Sage\Titles;
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
  $featured_image = Firebelly\Media\get_treated_image($post);
  $project_comparison_images = get_post_meta($post->ID, '_cmb2_project_comparison_images', true);
  $video_url = $video_id = '';
  $project_video = get_post_meta($post->ID, '_cmb2_project_video', true);
  if (filter_var($project_video, FILTER_VALIDATE_URL)) {
    $video_url = $project_video;
  } else {
    $video_id = $project_video;
  }
?>

<?php if (!empty($header_images)): ?>
  <?php if (sizeof($header_images) > 1): ?>
    <div class="header-carousel is-hidden container">
      <?php foreach ($header_images as $attachment_id => $attachment_url): ?>
        <?php $carousel_image = Firebelly\Media\get_treated_image('image', ['thumb_id' => $attachment_id]); ?>
        <div class="image" <?= $carousel_image ?>></div>
      <?php endforeach ?>
    </div>
  <?php else: ?>
    <div class="header-image container">
      <?php foreach ($header_images as $attachment_id => $attachment_url): ?>
        <?php $carousel_image = Firebelly\Media\get_treated_image('image', ['thumb_id' => $attachment_id]); ?>
        <div class="image" <?= $carousel_image ?>></div>
      <?php endforeach ?>
    </div>
  <?php endif ?>
<?php elseif (!empty($featured_image)): ?>
  <div class="header-image container">
    <div class="image" <?= $featured_image ?>></div>
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
          <div class="description">
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
              <div class="callout-copy">
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
            $file = Firebelly\Media\get_treated_image('image', ['thumb_id' => $image['file_id'], 'size' => $crop, 'output' => false]);
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
          $beforeImage = wp_get_attachment_image_src($image['before_id'], 'project_large', false, '');
          $afterImage = wp_get_attachment_image_src($image['after_id'], 'project_large', false, '');
        ?>
        <div class="cocoen">
          <img src="<?= $beforeImage[0] ?>" alt="">
          <img src="<?= $afterImage[0] ?>" alt="">
        </div>
      <?php endforeach ?>
    </div>
  </div>
<?php endif ?>

<?php if (!empty($project_video)): ?>
  <div class="project-video-container container">
    <div id="project-video" class="project-video" <?= (!empty($video_url)) ? ' data-url="'. $video_url .'"' : ' data-id="'. $video_id .'"' ?>></div>
  </div>
<?php endif ?>

<?php get_template_part('templates/project-nav'); ?>