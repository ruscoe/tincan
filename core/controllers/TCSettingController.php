<?php

namespace TinCan\controllers;

use TinCan\controllers\TCController;
use TinCan\objects\TCObject;
use TinCan\objects\TCSetting;

/**
 * Setting controller.
 *
 * @package TinCan
 * @author  Dan Ruscoe <danruscoe@protonmail.com>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 * @since   0.16
 */
class TCSettingController extends TCController
{
    /**
     * Updates settings.
     *
     * @param array $submitted_fields The submitted settings.
     *
     * @return bool TRUE if the settings were updated, otherwise FALSE.
     *
     * @since 0.16
     */
    public function update_settings($submitted_fields)
    {
        // Boolean settings are controlled by checkboxes on the settings form.
        // An unchecked box results in no value for that field. This means we don't
        // know the setting has changed.
        // To work around this, empty values for missing boolean settings are set here.
        $conditions = [['field' => 'type', 'value' => 'bool']];
        $bool_settings = $this->db->load_objects(new TCSetting(), [], $conditions);

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

            $setting_results = $this->db->load_objects(new TCSetting(), [], $conditions);
            if (!empty($setting_results)) {
                $setting = reset($setting_results);
            }

            if (!empty($setting)) {
                switch ($setting->type) {
                    case 'bool':
                        $checked = filter_var($field_value, FILTER_SANITIZE_STRING);
                        $setting->value = ('on' === $checked) ? 'true' : 'false';

                        try {
                            $this->db->save_object($setting);
                        } catch (TCException $e) {
                            $this->error = TCObject::ERR_NOT_SAVED;
                            return false;
                        }

                        break;
                    default:
                        $setting->value = filter_var($field_value, FILTER_SANITIZE_STRING);

                        try {
                            $this->db->save_object($setting);
                        } catch (TCException $e) {
                            $this->error = TCObject::ERR_NOT_SAVED;
                            return false;
                        }
                }
            }
        }

        return true;
    }
}
