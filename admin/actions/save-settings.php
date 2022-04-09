<?php

require '../../tc-config.php';

require TC_BASE_PATH . '/includes/include-db.php';
require TC_BASE_PATH . '/includes/include-objects.php';

$db = new TCData();

foreach ($_POST as $field_name => $field_value) {
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
        $setting->value = filter_var($field_value, FILTER_SANITIZE_STRING);
        $db->save_object($setting);
    }
}
