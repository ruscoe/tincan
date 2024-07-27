<?php

namespace TinCan\controllers;

use TinCan\content\TCImage;
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

    /**
     * Uploads an image for a setting.
     *
     * @param string $setting The setting name.
     * @param array  $file    The uploaded file.
     *
     * @return bool TRUE if the image was uploaded, otherwise FALSE.
     *
     * @since 0.16
     */
    public function upload_setting_image($setting, $file)
    {
        // Check the given setting is an image type setting.
        $setting_objects = $this->db->get_indexed_objects(new TCSetting(), 'setting_name');
        $image_setting = $setting_objects[$setting];

        if (empty($image_setting) || ($image_setting->type !== 'image')) {
            $this->error = TCImage::ERR_FILE_GENERAL;
            return false;
        }

        if ((empty($file) || UPLOAD_ERR_OK !== $file['error'])) {
            $this->error = TCImage::ERR_FILE_GENERAL;
            return false;
        }

        $image_data = getimagesize($file['tmp_name']);

        $image = new TCImage();
        $image->width = $image_data[0];
        $image->height = $image_data[1];
        $image->file_type = $image_data[2];
        $image->mime_type = $image_data['mime'];
        $image->file_size = $file['size'];

        // Check for valid file type.
        if (!$image->is_valid_type()) {
            $this->error = TCImage::ERR_FILE_TYPE;
            return false;
        }

        // Check file size.
        if (!$image->is_valid_size()) {
            $this->error = TCImage::ERR_FILE_SIZE;
            return false;
        }

        // The image filename is identical to the setting name.
        // TODO: Eventually integrate this into a media management system.
        $target_file = $setting.'.jpg';
        $target_full_path = getenv('TC_UPLOADS_PATH').'/'.$target_file;

        if (!move_uploaded_file($file['tmp_name'], $target_full_path)) {
            $this->error = TCImage::ERR_FILE_GENERAL;
            return false;
        }

        // Update setting value with new filename.
        $image_setting->value = '/uploads/'.$target_file;

        try {
            $this->db->save_object($image_setting);
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        return true;
    }

    /**
     * Deletes an image for a setting.
     *
     * @param string $setting The setting name.
     *
     * @return bool TRUE if the image was deleted, otherwise FALSE.
     *
     * @since 0.16
     */
    public function delete_setting_image($setting)
    {
        // Check the given setting exists and is an image type setting.
        $setting_objects = $this->db->get_indexed_objects(new TCSetting(), 'setting_name');
        $image_setting = $setting_objects[$setting];

        if (empty($image_setting) || ($image_setting->type !== 'image')) {
            $this->error = TCImage::ERR_FILE_GENERAL;
            return false;
        }

        // Image settings are given an empty filename rather than being deleted.
        // This just avoids complications uploading a new image later.
        $image_setting->value = '';

        try {
            $this->db->save_object($image_setting);
        } catch (TCException $e) {
            $this->error = TCObject::ERR_NOT_SAVED;
            return false;
        }

        return true;
    }
}
