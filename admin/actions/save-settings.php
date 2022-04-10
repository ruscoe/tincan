<?php

require '../../tc-config.php';

require TC_BASE_PATH . '/includes/include-db.php';
require TC_BASE_PATH . '/includes/include-objects.php';

$submitted_fields = $_POST;

$db = new TCData();

// Boolean settings are controlled by checkboxes on the settings form.
// An unchecked box results in no value for that field. This means we don't
// know the setting has changed.
// To work around this, empty values for missing boolean settings are set here.
$conditions = array(array('field' => 'type', 'value' => 'bool'));
$bool_settings = $db->load_objects(new TCSetting(), array(), $conditions);

foreach ($bool_settings as $setting) {
    if (!isset($submitted_fields[$setting->setting_name])) {
        $submitted_fields[$setting->setting_name] = null;
    }
}

foreach ($submitted_fields as $field_name => $field_value) {
    $conditions = array(
      array(
        'field' => 'setting_name',
        'value' => $field_name
      )
    );

    $setting = null;

    $setting_results = $db->load_objects(new TCSetting(), array(), $conditions);
    if (!empty($setting_results)) {
        $setting = reset($setting_results);
    }

    if (!empty($setting)) {
        switch ($setting->type) {
        case 'bool':
        $checked = filter_var($field_value, FILTER_SANITIZE_STRING);
        $setting->value = ($checked === 'on') ? 'true' : 'false';
        $db->save_object($setting);
        break;
        default:
        $setting->value = filter_var($field_value, FILTER_SANITIZE_STRING);
        $db->save_object($setting);
      }
    }
}
