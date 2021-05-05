<?php 
     require_once '../core/init.php';
     if(!is_logged_in()){
          login_error_redirect();
     }
     include 'includes/head.php';
     include 'includes/navigation.php';

     $sql = "SELECT * FROM brand ORDER BY brand";
     $results = $db->query($sql);
     $errors = array();

     if(isset($_GET['edit']) && !empty($_GET['edit'])){
          $edit_id = (int)$_GET['edit'];
          $edit = sanitize($edit_id);
          $sql2 = "SELECT * FROM brand WHERE id = ' $edit_id'";
          $edit_result = $db->query($sql2);
          $eBrand = mysqli_fetch_assoc($edit_result);

     }
     
     
     
     
     
     if(isset($_GET['delete']) && !empty($_GET['delete'])){
          $delete_id = (int)$_GET['delete'];
          $delete_id = sanitize($delete_id);
          $sql = "DELETE FROM brand WHERE id = ' $delete_id'";
          $db->query($sql);
          header('Location: brands.php');
     }

     if (isset($_POST['add_submit'])){
          $brand = sanitize($_POST['brand']);
          if($_POST['brand'] == ''){
               $errors[] .= 'You must enter a brand.';
          }

          $sql = "SELECT * FROM brand WHERE brand = '$brand'";
          if (isset($_GET['edit'])){
               $sql = "SELECT * FROM brand WHERE brand = '$brand' AND id != '$edit_id'";
          }
          $result = $db->query($sql);
          $count = mysqli_num_rows($result);
          if ($count > 0) {
               $errors[] .= $brand . ' already exist. Please enter another brand.';
          }

          if(!empty($errors)){
               echo display_errors($errors);
          }else{
               $sql = "INSERT INTO brand(brand) VALUES ('$brand')";
               if (isset($_GET['edit'])){
                    $sql = "UPDATE brand SET brand = '$brand' WHERE id = '$edit_id'";
               }
               $db->query($sql);
               header('Location: brands.php');         
          }
     }

?>
     <h2 class="text-center">Brands</h2>
     
     <div class="text-center">
          <form class="form-inline" action="brands.php<?=((isset($_GET['edit']))?'?edit=' . $edit_id:'');?>" method="post">
               <div class="form-group">
                    <?php
                         $brand_value = '';
                         if(isset($_GET['edit'])){
                              $brand_value = $eBrand['brand'];
                         }else{
                              if(isset($_POST['brand'])){
                                   $brand_value = sanitize($_POST['brand']);
                              }
                         }
                    ?>
                    <label for="brand"><?= ((isset($_GET['edit']))?'Edit' : 'Add'); ?> a brand</label>
                    <input type="text" name="brand" id="brand" class="form-control" value="<?= $brand_value ?>">
                    <input type="submit" name="add_submit" value="<?= ((isset($_GET['edit']))?'Edit' : 'Add'); ?>" class="btn btn-success">
                    <?php if(isset($_GET['edit'])) : ?>
                         <a href="brands.php" class="btn btn-default">Cancel</a>
                    <?php endif; ?>
               </div>
          </form>
     </div>
     
     <table class="table table-bordered table-striped table-auto table-condensed">
          <thead>
               <th></th>
               <th>Brand</th>
               <th></th>
          </thead>
          <?php while($brand = mysqli_fetch_assoc($results)) : ?>
          <tbody>
               <tr>
                    <td>
                         <a href="brands.php?edit=<?= $brand['id'] ?>" class="btn btn-outline-primary">
                              <span class="fa fa-edit"></span>
                         </a>
                    </td>
                    <td><?= $brand['brand'] ?></td>
                    <td>
                         <a href="brands.php?delete=<?= $brand['id'] ?>" class="btn btn-outline-primary">
                              <span class="fa fa-trash"></span>
                         </a>
                    </td>
               </tr>
          <?php endwhile; ?>
          </tbody>
     </table>
<?php 
     include 'includes/footer.php';
?>