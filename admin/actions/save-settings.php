<?php

use TinCan\db\TCData;
use TinCan\objects\TCSetting;
use TinCan\objects\TCUser;
use TinCan\user\TCUserSession;

/**
 * Tin Can save settings handler.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */

require getenv('TC_BASE_PATH').'/vendor/autoload.php';

$submitted_fields = $_POST;

$db = new TCData();
$settings = $db->load_settings();

// Get logged in user.
$session = new TCUserSession();
$session->start_session();
$user_id = $session->get_user_id();
$user = (!empty($user_id)) ? $db->load_user($user_id) : null;

// Check for admin user.
if (empty($user) || !$user->can_perform_action(TCUser::ACT_ACCESS_ADMIN)) {
    // Not an admin user; redirect to log in page.
    header('Location: /index.php?page='.$settings['page_log_in']);
    exit;
}

// Boolean settings are controlled by checkboxes on the settings form.
// An unchecked box results in no value for that field. This means we don't
// know the setting has changed.
// To work around this, empty values for missing boolean settings are set here.
$conditions = [['field' => 'type', 'value' => 'bool']];
$bool_settings = $db->load_objects(new TCSetting(), [], $conditions);

foreach ($bool_settings as $setting) {
    if (!isset($submitted_fields[$setting->setting_name])) {
        $submitted_fields[$setting->setting_name] = null;
    }
}

foreach ($submitted_fields as $field_name => $field_value) {
    $conditions = [
        [
          'field' => 'setting_name',
          'value' => $field_name,
        ],
      ];

    $setting = null;

    $setting_results = $db->load_objects(new TCSetting(), [], $conditions);
    if (!empty($setting_results)) {
        $setting = reset($setting_results);
    }

    if (!empty($setting)) {
        switch ($setting->type) {
        case 'bool':
            $checked = filter_var($field_value, FILTER_SANITIZE_STRING);
            $setting->value = ('on' === $checked) ? 'true' : 'false';
            $db->save_object($setting);
            break;
        default:
            $setting->value = filter_var($field_value, FILTER_SANITIZE_STRING);
            $db->save_object($setting);
        }
    }
}

$destination = '/admin/index.php?page='.$settings['admin_page_forum_settings'];

header('Location: '.$destination);
exit;
