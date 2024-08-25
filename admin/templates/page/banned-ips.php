<?php

use TinCan\db\TCData;
use TinCan\objects\TCBannedIp;

/**
 * Page template for banned IP addresses.
 *
 * @since 1.0.0
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

$page = $data['page'];

$db = new TCData();

$banned_ips = $db->load_objects(new TCBannedIp());

$banned_ip_string = '';

foreach ($banned_ips as $banned_ip) {
    $banned_ip_string .= $banned_ip->ip . ' ';
}

?>

<h1><?php echo $page->page_title; ?></h1>

<form id="edit-banned-ips" action="/admin/actions/update-banned-ips.php" method="POST">
  <div class="fieldset textarea">
    <label for="banned_ips">Banned IP Addresses</label>
    <div class="field">
      <textarea name="banned_ips" rows="20" cols="30"><?php echo $banned_ip_string; ?></textarea>
      Separate with a space.
    </div>
  </div>

  <div class="fieldset button">
    <input class="submit-button" type="submit" value="Update banned IPs" />
  </div>
</form>
