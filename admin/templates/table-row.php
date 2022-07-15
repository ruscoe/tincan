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
  <td><a href="<?php echo $data['view_url']; ?>" target="_blank"><?php echo $data['title']; ?></a></td>
  <td><a href="<?php echo $data['view_url']; ?>" target="_blank">View</a></td>
  <td><a href="<?php echo $data['edit_url']; ?>">Edit</a></td>
  <td>
    <?php
      echo (!empty($data['delete_url'])) ? '<a href="'.$data['delete_url'].'">Delete</a>' : '<strike>Delete</strike>';
    ?>
  </td>
</tr>
