<?php
     //require_once $_SERVER['DOCUMENT_ROOT'] . '/tutorial/core/init.php';
     require_once '../core/init.php';
     if(!is_logged_in()){
          login_error_redirect();
     }
     include 'includes/head.php';
     include 'includes/navigation.php';

     $sql = "SELECT * FROM categories WHERE parent = 0";
     $result = $db->query($sql);

     $errors = array();

     if(isset($_GET['edit']) && !empty($_GET['edit'])){
          $edit_id = (int)$_GET['edit'];
          $edit = sanitize($edit_id);
          $sql2 = "SELECT * FROM categories WHERE id = ' $edit_id'";
          $edit_result = $db->query($sql2);
          $eCategory = mysqli_fetch_assoc($edit_result);

     }

     if(isset($_GET['delete']) && !empty($_GET['delete'])){
          $delete_id = (int)$_GET['delete'];
          $delete_id = sanitize($delete_id);
          $sql = "DELETE FROM categories WHERE id = '$delete_id' OR parent = '$delete_id'";
          $db->query($sql);
          header('Location: categories.php');
          // añadir algo para borrar categorías huerfanas
     }



     
     if(isset($_POST) && !empty($_POST)){
          $post_parent = sanitize($_POST['parent']);
          $category = sanitize($_POST['category']);
          
          $sqlForm = "SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent'";
          $Fresult = $db->query($sqlForm);
          $count = mysqli_num_rows($Fresult);

          if($category == ''){
               $errors[] .= 'The category cannot be left blank.';
          }
          if($count > 0){
          $errors[] .= $category . ' already exist.';  
          }

          if(!empty($errors)){
               $display = display_errors($errors); ?>
               <script>
                    jQuery('document').ready(function(){
                         jQuery('#errors').html('<?= $display ?>');
                    });
               </script>
          <?php }else{
               if (isset($_GET['edit'])){
                    $sql = "UPDATE categories SET category = '$category', parent = '$post_parent' WHERE id = '$edit_id'";
               }else{
                    $sql = "INSERT INTO categories(category, parent) VALUES('$category','$post_parent')";
               }
               $db->query($sql);
               header('Location: categories.php');
          }
     }

     $category_value = '';
     $parent_value = '0';
     if(isset($_GET['edit'])){
         $category_value =  $eCategory['category'];
         $parent_value = $eCategory['parent'];
     }else{
          if(isset($_POST)){
          $category_value = $category;
          $parent_value = $post_parent;
          }
     }
?>

<h2 class="text-center">Categories</h2>
<div class="row">
     <div class="col-md-6">
          <form action="categories.php<?=((isset($_GET['edit']))?'?edit=' . $edit_id:'');?>" class="form" method="post">
               <legend><?= ((isset($_GET['edit']))?'Edit' : 'Add'); ?> a category</legend>
               <div id="errors"></div>
               <div class="form-group">
                    <label for="parent">Parent</label>
                    <select name="parent" id="parent" class="form-control">
                              <option value="0" <?= (($parent_value == 0)?' selected="selected"' : ''); ?>>Parent</option>
                         <?php while($parent = mysqli_fetch_assoc($result)) : ?>
                              <option value="<?= $parent['id']; ?>"<?= (($parent_value == $parent['id'])?' selected="selected"' : ''); ?>><?= $parent['category']; ?></option>
                         <?php endwhile;?>
                    </select>
               </div>
               <div class="form-group">
                    <label for="category">Category</label>
                    <input type="text" name="category" id="category" class="form-control" value="<?= $category_value; ?>">
               </div>
               <div class="form-group">
                    <input type="submit" value="<?= ((isset($_GET['edit']))?'Edit' : 'Add'); ?>" class="btn btn-success">
                    <?php if(isset($_GET['edit'])) : ?>
                         <a href="categories.php" class="btn btn-default">Cancel</a>
                    <?php endif; ?>
               </div>
          </form>
     </div>
     
     
     
     
     <div class="col-md-6">
          <table class="table table-bordered">
               <thead>
                    <th>Category</th>
                    <th>Parent</th>
                    <th></th>
               </thead>
               <tbody>
                    <?php
                    $sql = "SELECT * FROM categories WHERE parent = 0";
                    $result = $db->query($sql);
                    while($parent = mysqli_fetch_assoc($result)) : 
                         $parent_id = (int)$parent['id'];
                         $sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
                         $parentResult = $db->query($sql2);
                    ?>
                    <tr>
                         <td><?= $parent['category']; ?></td>
                         <td>Parent</td>
                         <td>
                              <a href="categories.php?edit=<?= $parent['id']; ?>" class="btn btn-outline-primary">
                                   <span class="fa fa-edit"></span>
                              </a>
                              <a href="categories.php?delete=<?= $parent['id']; ?>" class="btn btn-outline-primary">
                                   <span class="fa fa-trash"></span>
                              </a>
                         </td>
                    </tr>
                    <?php while($child = mysqli_fetch_assoc($parentResult)) : ?>
                    <tr class="bg-info">
                         <td><?= $child['category']; ?></td>
                         <td><?= $parent['category']; ?></td>
                         <td>
                              <a href="categories.php?edit=<?= $child['id']; ?>" class="btn btn-outline-primary">
                                   <span class="fa fa-edit"></span>
                              </a>
                              <a href="categories.php?delete=<?= $child['id']; ?>" class="btn btn-outline-primary">
                                   <span class="fa fa-trash"></span>
                              </a>
                         </td>
                    </tr>
                         <?php endwhile; ?>
                    <?php endwhile; ?>
               </tbody>
          </table>
     </div>
</div>

<?php
     include 'includes/footer.php';
?>