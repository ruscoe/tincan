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
  <td><a href="<?php echo $data['url']; ?>"><?php echo $data['title']; ?></a></td>
  <td><a href="/admin/?page=<?php echo $data['view_page_id']; ?>&object=<?php echo $data['object_id']; ?>" target="_blank">View</a></td>
  <td><a href="/admin/?page=<?php echo $data['edit_page_id']; ?>&object=<?php echo $data['object_id']; ?>">Edit</a></td>
  <td><a href="#">Delete</a></td>
</tr>
