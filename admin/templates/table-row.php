<?php
/**
 * Template used to display a generic table row.
 *
 * @package Tin Can Forum
 * @since 0.01
 * @author Dan Ruscoe danruscoe@protonmail.com
 */
?>

<tr>
  <td><a href="<?=$data['url']?>"><?=$data['title']?></a></td>
  <td><a href="/admin/?page=<?=$data['view_page_id']?>&object=<?=$data['object_id']?>" target="_blank">View</a></td>
  <td><a href="/admin/?page=<?=$data['edit_page_id']?>&object=<?=$data['object_id']?>">Edit</a></td>
  <td><a href="#">Delete</a></td>
</tr>
