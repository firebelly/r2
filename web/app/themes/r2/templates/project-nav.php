<?php
  use Roots\Sage\Titles;

  if(!empty(get_next_post())) {
    $previous_post = get_next_post();
  } else {
    $last = new WP_Query('post_type=project&posts_per_page=1&order=DESC');
    $previous_post = $last->posts[0];
  };

  if(!empty(get_previous_post())) {
    $next_post = get_previous_post();
  } else {
    $first = new WP_Query('post_type=project&posts_per_page=1&order=ASC');
    $next_post = $first->posts[0];
  };
?>

<div class="project-navigation grid">
  <div class="previous-container col-1-2">
    <div class="-inner container">
      <?php if (!empty($previous_post)): ?>
        <?php
          $previous_title = $previous_post->post_title;
          $previous_title_first = get_post_meta($previous_post->ID, '_cmb2_title_large', true);
          $previous_title_second = get_post_meta($previous_post->ID, '_cmb2_title_small', true);
          $previous_locality = get_post_meta($previous_post->ID, '_cmb2_locality', true);
        ?>
        <p class="project-link"><a href="<?= get_permalink($previous_post) ?>">Previous</a></p>
        <h4 class="project-title">
          <?php if (!empty($previous_title_first)): ?>
            <span class="first"><?= $previous_title_first ?></span>
            <?php if (!empty($previous_title_second)): ?>
              <span class="second"><?= $previous_title_second ?></span>
            <?php endif ?>
          <?php else: ?>
            <?= $previous_title ?>
          <?php endif ?>
        </h4>
        <?php if (!empty($previous_locality)): ?>
          <p class="locality"><?= $previous_locality ?></p>
        <?php endif ?>
      <?php endif ?>
    </div>
  </div>

  <div class="next-container col-1-2">
    <?php if (!empty($next_post)): ?>
      <?php
        $next_thumb = Firebelly\Media\get_treated_image($next_post, ['size' => 'project_nav']);
      ?>
      <div class="-inner container">
        <?php
          $next_title = $next_post->post_title;
          $next_title_first = get_post_meta($next_post->ID, '_cmb2_title_large', true);
          $next_title_second = get_post_meta($next_post->ID, '_cmb2_title_small', true);
          $next_locality = get_post_meta($next_post->ID, '_cmb2_locality', true);
        ?>
        <p class="project-link"><a href="<?= get_permalink($next_post) ?>" data-hover-pair="nextProject">Next</a></p>
        <h4 class="project-title">
          <?php if (!empty($next_title_first)): ?>
            <span class="first"><?= $next_title_first ?></span>
            <?php if (!empty($next_title_second)): ?>
              <span class="second"><?= $next_title_second ?></span>
            <?php endif ?>
          <?php else: ?>
            <?= $next_title ?>
          <?php endif ?>
        </h4>
        <?php if (!empty($next_locality)): ?>
          <p class="locality"><?= $next_locality ?></p>
        <?php endif ?>
      </div>
      <a class="next-thumb" href="<?= get_permalink($next_post) ?>" <?= $next_thumb ?> data-hover-pair="nextProject"></a>
    <?php endif ?>
  </div>
</div>