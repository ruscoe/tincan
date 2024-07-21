<?php
/**
 * Template used to display a generic table row.
 *
 * @since 0.01
 *
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
?>

<tr>
  <?php
    foreach ($data as $item) {
        switch ($item['type']) {
            case 'text':
                echo '<td>'.$item['value'].'</td>';
                break;
            case 'link':
                echo '<td><a href="'.$item['url'].'">'.$item['value'].'</a></td>';
                break;
            default:
                echo '<td>&nbsp;</td>';
        }
    }
?>
</tr>
