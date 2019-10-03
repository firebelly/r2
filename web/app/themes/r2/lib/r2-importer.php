<?php
/**
 * Custom r2 importer for people, media, projects
 */

namespace Firebelly\Import;

// show link to import form
add_action('admin_menu', function() {
  add_management_page( 'r2 Importer', 'r2 Importer', 'publish_posts', 'r2-importer', __NAMESPACE__.'\fb_csv_import_form');
});
function fb_csv_import_form() {
  if ('POST' == $_SERVER['REQUEST_METHOD']) {
    $importer = new \Firebelly\Import\CSVImporter;
    if (!empty($_REQUEST['import-projects'])) {
      $importer->handle_post(false, 'project');
    } elseif (!empty($_REQUEST['import-people'])) {
      $importer->handle_post(false, 'people');
    } elseif (!empty($_REQUEST['import-media'])) {
      $importer->handle_post(false, 'media');
    } else {
      echo '?!';
    }
  }
?>
  <div class="wrap">
      <h2>r2 Importer</h2>
      <form method="post" id="csv-upload-form" enctype="multipart/form-data">
        <fieldset>
          <label for="csv_import">CSV file:</label>
          <input name="csv_import[]" id="csv-import" type="file" multiple>
        </fieldset>

        <p class="submit">
          <input type="submit" class="button" name="import-projects" value="Import Projects">
          &nbsp;
          <input type="submit" class="button" name="import-people" value="Import People">
          &nbsp;
          <input type="submit" class="button" name="import-media" value="Import Media">
        </p>
      </form>
  </div>
<?php
}

class CSVImporter {
  private $log = [];
  private $ajax = false;
  private $team_member_categories = [];
  private $project_locations = [];

  function printMessages() {
      if (!$this->ajax && !empty($this->log)) {

      // messages HTML {{{
?>

<div class="wrap">
  <?php if (!empty($this->log['error'])): ?>

  <div class="error">

      <?php foreach ($this->log['error'] as $error): ?>
          <p><?php echo $error; ?></p>
      <?php endforeach; ?>

  </div>

  <?php endif; ?>

  <?php if (!empty($this->log['notice'])): ?>

  <div class="updated">

      <?php foreach ($this->log['notice'] as $notice): ?>
          <p><?php echo $notice; ?></p>
      <?php endforeach; ?>

  </div>

  <?php endif; ?>
</div><!-- end wrap -->

<?php
      // end messages HTML }}}

          $this->log = [];
      }
  }


  /**
   * Import posts of $type from CSV
   *
   * @return void
   */
  function handle_post($ajax=false, $type) {
    $this->ajax = $ajax;

    // rejigger HTML5 multiple file upload array format
    $files = [];
    $fdata = $_FILES['csv_import'];
    if (!empty($fdata)) {
      if ( is_array($fdata['name']) ) {
        for ($i = 0; $i<count($fdata['name']); ++$i) {
          if (!empty($fdata['name'][$i])) {
            $files[] = array(
              'name'     => $fdata['name'][$i],
              'type'     => $fdata['type'][$i],
              'tmp_name' => $fdata['tmp_name'][$i],
              'error'    => $fdata['error'][$i],
              'size'     => $fdata['size'][$i]
              );
          }
        }
      } else $files[] = $fdata;
    }

    if (empty($files)) {
      $this->log['error'][] = 'No file specified for import.';
      $this->printMessages();
      return $this->log;
    }

    if (!current_user_can('publish_pages') || !current_user_can('publish_posts')) {
      $this->log['error'][] = 'You don\'t have the permissions to publish posts and pages.';
      $this->printMessages();
      return $this->log;
    }

    $time_start = microtime(true);
    $i = $skipped = $imported = 0;

    // Cache terms
    $this->team_member_categories = get_terms('team_member_category', array('hide_empty' => 0));
    $this->project_locations = get_terms('project_location', array('hide_empty' => 0));

    foreach($files as $fileUpload) {

      $file = $fileUpload['tmp_name'];

      // Map csv headers to associative array
      $csv = array_map('str_getcsv', file($file));
      array_walk($csv, function(&$a) use ($csv) {
        $a = array_combine($csv[0], $a);
      });
      array_shift($csv); // remove column header

      if (empty($csv)) {
        $this->log['error'][] = 'Failed to load file, aborting.';
        $this->printMessages();
        return $this->log;
      }

      foreach ($csv as $csvRow) {
        if ($postId = $this->createPost($csvRow, $type)) {
            $imported++;
        } else {
            $skipped++;
        }
      }

      // remove temp upload file
      if (file_exists($file)) {
        @unlink($file);
      }
      $i++;
    }

    $exec_time = microtime(true) - $time_start;
    if ($skipped) {
      $this->log['notice'][] = "<b>Skipped {$skipped} entries.</b>";
    }
    $this->log['notice'][] = sprintf("<b>Imported %s entries in %.2f seconds.</b>", $imported, $exec_time);
    $this->log['stats']['entries'] = $imported;
    $this->log['stats']['exec_time'] = sprintf("%.2f", $exec_time);
    $this->printMessages();
    return $this->log;
  }

  function createPost($data, $type) {
    global $wpdb;

    $postType = $type;
    if ($postType=='people') $postType = 'team_member';
    if ($postType=='media') $postType = 'media_publication';

    // Check if post exists
    $existing_post = get_page_by_title($data['title'], OBJECT, $postType);

    if (!empty($existing_post)) {
      $this->log['error'][] = "<a target=\"_blank\" href=\"".get_permalink($existing_post)."\">{$data['title']}</a> already exists. <a target=\"_blank\" href=\"".get_edit_post_link($existing_post)."\">Edit</a>";

      return false;
    }

    $post_args = [
      'post_title'   => $data['title'],
      'post_status'  => 'publish',
      'post_content' => (!empty($data['content']) ? $data['content'] : ''),
      'post_type'    => $postType,
      'post_author'  => get_current_user_id(),
    ];
    $id = wp_insert_post($post_args);

    if ($postType == 'team_member') {
      foreach($this->team_member_categories as $term) {
        if ($data['type']=='Principals') $data['type'] = 'Principal';
        if (strpos(strtolower($term->name), strtolower($data['type'])) !== false) {
          wp_set_object_terms($id, [$term->term_id], 'team_member_category');
        }
      }
      update_post_meta($id, '_cmb2_member_title', $data['person_title']);
      update_post_meta($id, '_cmb2_member_email', $data['email']);
      update_post_meta($id, '_cmb2_member_linkedin', $data['linkedin']);
      update_post_meta($id, '_cmb2_member_phone', $data['phone']);
      if (!empty($data['bio'])) {
        update_post_meta($id, '_cmb2_member_bio', $data['bio']);
      }

    } else if ($postType == 'media_publication') {

      // Try to find publication name
      if (preg_match('#<p><span style="text-decoration: underline;">([^<]+)</span></p>#', $data['description'], $m)) {
        update_post_meta($id, '_cmb2_publication_name', $m[1]);
      }
      if (!empty($data['url'])) {
        update_post_meta($id, '_cmb2_media_url', $data['url']);
      }
      // Try to find publication date
      if (preg_match('#20(\d\d)#', $data['description'], $m)) {
        update_post_meta($id, '_cmb2_media_date', '01/01/'.$m[1]);
      } else {
        update_post_meta($id, '_cmb2_media_date', '01/01/2019');
      }

    } else if ($postType == 'project') {

      update_post_meta($id, '_cmb2_locality', $data['location']);
      update_post_meta($id, '_cmb2_square_footage', $data['square_footage']);
      update_post_meta($id, '_cmb2_description', $data['description']);

      // Try to set location term
      if (preg_match('#(.*) —#', $data['city_name'], $m)) {
        $city = $m[1];
        foreach($this->project_locations as $term) {
          if (strpos(strtolower($term->name), strtolower($city)) !== false) {
            wp_set_object_terms($id, [$term->term_id], 'project_location');
          }
        }
      }

    }

    $this->log['notice'][] = "<a target=\"_blank\" href=\"".get_permalink($id)."\">{$data['title']}</a> {$postType} added OK! <a target=\"_blank\" href=\"".get_edit_post_link($id)."\">Edit</a>";
    return $id;

  }
}
