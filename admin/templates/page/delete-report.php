<?php

use TinCan\db\TCData;
use TinCan\objects\TCUser;
use TinCan\objects\TCObject;
use TinCan\objects\TCReport;
use TinCan\template\TCTemplate;

/**
 * Page template for report deletion.
 *
 * @since 0.16
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
$report_id = filter_input(INPUT_GET, 'report_id', FILTER_SANITIZE_NUMBER_INT);
$error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_STRING);

$db = new TCData();

$report = $db->load_object(new TCReport(), $report_id);

if (empty($error) && empty($report)) {
    $error = TCObject::ERR_NOT_FOUND;
}

// Error handling.
if (!empty($error)) {
    switch ($error) {
        case TCObject::ERR_NOT_FOUND:
            $error_msg = 'Report not found.';
            break;
        case TCObject::ERR_NOT_SAVED:
            $error_msg = 'Report could not be deleted.';
            break;
        default:
            $error_msg = $error;
    }

    TCTemplate::render('form-errors', $data['settings']['theme'], ['errors' => [$error_msg], 'page' => $data['page']]);
}

if (!empty($report)) {
    ?>
    <h1>Really delete report?</h1>

    <form id="delete-object" action="/admin/actions/delete-report.php" method="POST">
      <input type="hidden" name="report_id" value="<?php echo $report->report_id; ?>" />

      <div class="fieldset button">
        <input class="submit-button" type="submit" value="Delete Report" />
      </div>
    </form>
    <?php
}
