<?php
$title = get_post_meta($team_member->ID, '_cmb2_member_title', true);
$image = \Firebelly\Media\get_post_thumbnail($team_member->ID);
$category = \Firebelly\Utils\get_first_term($team_member);
$email = $phone = $linkedin = '';
$email = get_post_meta($team_member->ID, '_cmb2_member_email', true);
$phone = get_post_meta($team_member->ID, '_cmb2_member_phone', true);
$linkedin = get_post_meta($team_member->ID, '_cmb2_member_linkedin', true);
?>
<article class="team-member <?= $category->slug ?><?= $category->slug === 'principle' ? ' with-modal' : ''; ?> animation-item col-md-1-2 col-lg-1-4">
  <div class="-inner">
    <div class="member-image" style="background-image:url('<?= $image ?>');"></div>
    <h4><?= $team_member->post_title ?></h4>
    <h5><?= $title ?></h5>
    <div class="contact-info">
      <?php if (!empty($email)): ?>
        <p><span>E</span> <?= $email ?></p>
      <?php endif ?>
      <?php if (!empty($phone)): ?>
        <p><span>T</span> <?= $phone ?></p>
      <?php endif ?>
      <?php if (!empty($linkedin)): ?>
        <p><span>@</span> <a href="<?= $linkedin ?>" target="_blnk">LinkedIn</a></p>
      <?php endif ?>
    </div>
  </div>
</article>
