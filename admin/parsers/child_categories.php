<?php  // listener
     require_once '../../core/init.php';

     $parentID = (int)$_POST['parentID'];
     $selected = sanitize($_POST['selected']);
     $childQUERY = $db->query("SELECT * FROM categories WHERE parent = '$parentID' ORDER BY category");

     ob_start();
?>
     <option value=""></option>
     <?php
          while($child = mysqli_fetch_assoc($childQUERY)) :
     ?>
          <option value="<?= $child['id']; ?>"<?= (($selected == $child['id'])? ' selected' : ''); ?>><?= $child['category']; ?></option>
     <?php
          endwhile; 
     ?>
<?php 
     echo ob_get_clean();
?>