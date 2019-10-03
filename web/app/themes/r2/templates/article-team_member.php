<?php
$title = get_post_meta($team_member->ID, '_cmb2_member_title', true);
$image = \Firebelly\Media\get_post_thumbnail($team_member->ID);
$category = \Firebelly\Utils\get_first_term($team_member, 'team_member_category');
$email = $phone = $linkedin = '';
$email = get_post_meta($team_member->ID, '_cmb2_member_email', true);
$phone = get_post_meta($team_member->ID, '_cmb2_member_phone', true);
$linkedin = get_post_meta($team_member->ID, '_cmb2_member_linkedin', true);
$bio = get_post_meta($team_member->ID, '_cmb2_member_bio', true);
?>
<article class="team-member grid-item <?= $category->slug ?><?= $category->slug === 'principal' ? ' with-modal' : ''; ?> col-md-1-2 col-lg-1-4" data-photo="<?= $image ?>">
  <div class="-inner">
    <div class="member-image" style="background-image:url('<?= $image ?>');"></div>
    <div class="member-info">
      <h4 class="member-name"><?= $team_member->post_title ?></h4>
      <h5><?= $title ?></h5>
      <div class="contact-info">
        <?php if (!empty($email)): ?>
          <p><span>E</span> <a href="mailto:<?= $email ?>"><?= $email ?></a></p>
        <?php endif ?>
        <?php if (!empty($phone)): ?>
          <p><span>T</span> <?= $phone ?></p>
        <?php endif ?>
        <?php if (!empty($linkedin)): ?>
          <p><span>@</span> <a href="<?= $linkedin ?>" target="_blnk">LinkedIn</a></p>
        <?php endif ?>
      </div>
    </div>
    <?php if ($category->slug === 'principal' && !empty($bio)): ?>
      <div class="member-bio">
        <div class="-inner">
          <?= apply_filters('the_content', $bio) ?>
        </div>
      </div>
    <?php endif ?>
  </div>
</article>
