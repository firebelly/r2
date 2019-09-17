<?php
  $headquarters = \Firebelly\SiteOptions\get_option('headquarters');
  $contact_phone = \Firebelly\SiteOptions\get_option('contact_phone');
  $contact_emails = \Firebelly\SiteOptions\get_option('contact_emails');
  $instagram = \Firebelly\SiteOptions\get_option('instagram_id');
  $twitter = \Firebelly\SiteOptions\get_option('twitter_id');
  $facebook = \Firebelly\SiteOptions\get_option('facebook_id');
?>

<footer class="site-footer" role="contentinfo">
  <div class="container">
    <div class="grid">
      <div class="newsletter-column col-md-1-2">
        <div class="-inner">
          <h5>Newsletter Signup</h5>
          <?php get_template_part('templates/newsletter-signup'); ?>
        </div>
      </div>

      <div class="col-md-1-2 grid spaced">
        <div class="col-lg-1-2">
          <h2 class="footer-logo"><svg class="icon r2-companies" aria-hidden="true" role="presentation"><use xlink:href="#r2-companies"/></svg></h2>
          <?php if (!empty($headquarters)): ?>
            <?php foreach ($headquarters as $headquarters): ?>
              <div class="headquarters">
                <div class="-inner">
                  <h5><?= $headquarters['city'] ?> HQ</h5>
                  <p class="address">
                    <span property="schema:streetAddress"><?= $headquarters['stree_address'] ?></span>
                    <br><span property="schema:addressLocality"><?= $headquarters['city'] ?></span>, <abbr property="schema:addressRegion"><?= $headquarters['state'] ?></abbr> <span property="schema:postalCode"><?= $headquarters['postal_code'] ?></span>
                  </p>
                </div>
              </div>
            <?php endforeach ?>
          <?php endif ?>
        </div>

        <div class="contact-column col-lg-1-2">
          <?php if (!empty($contact_phone)): ?>
            <div class="contact-phone">
              <h5>General Phone No.</h5>
              <a href="tel:<?= $contact_phone ?>"> <span property="schema:telephone" class="phone"><?= $contact_phone ?></span></a>
            </div>
          <?php endif; ?>

          <?php if (!empty($contact_emails)): ?>
            <div class="contact-emails">
              <?php foreach ($contact_emails as $contact_email): ?>
                <div class="contact-email">
                  <h5><?= $contact_email['label'] ?></h5>
                  <p><a href="mailto:<?= $contact_email['email'] ?>"><?= $contact_email['email'] ?></a></p>
                </div>
              <?php endforeach ?>
            </div>
          <?php endif ?>

          <div class="-bottom">
            <?php if (!empty($instagram) || !empty($twitter) || !empty($facebook)): ?>
              <ul class="social-media-links">
                <?php if (!empty($instagram)): ?>
                  <li><a href="https://instagram.com/<?= $instagram ?>"><svg class="icon icon-instagram" aria-hidden="true" role="presentation"><use xlink:href="#icon-instagram"/></svg><span class="visually-hidden">Instagram</span></a></li>
                <?php endif ?>
                <?php if (!empty($twitter)): ?>
                  <li><a href="https://twitter/com/<?= $twitter ?>"><svg class="icon icon-twitter" aria-hidden="true" role="presentation"><use xlink:href="#icon-twitter"/></svg><span class="visually-hidden">Twitter</span></a></li>
                <?php endif ?>
                <?php if (!empty($facebook)): ?>
                  <li><a href="https://facebook.com/<?= $facebook ?>"><svg class="icon icon-facebook" aria-hidden="true" role="presentation"><use xlink:href="#icon-facebook"/></svg><span class="visually-hidden">Facebook</span></a></li>
                <?php endif ?>
              </ul>
            <?php endif ?>

            <p class="copyright">© <?= date('Y'); ?> R2 COMPANIES | All Rights Reserved</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>
