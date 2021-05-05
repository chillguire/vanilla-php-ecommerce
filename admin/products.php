<?php
     require_once '../core/init.php';
     if(!is_logged_in()){
          login_error_redirect();
     }
     include 'includes/head.php';
     include 'includes/navigation.php';


     if (isset($_GET['delete'])) {
          $id = sanitize($_GET['delete']);
          $db->query("UPDATE products SET deleted = 1 WHERE id = '$id'");
          header('Location: products.php');
     }



     $dbPath = '';

     if(isset($_GET['add']) || isset($_GET['edit'])){
          $brandQUERY = $db->query("SELECT * FROM brand ORDER BY brand");
          $parentQUERY = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");
          $title = ((isset($_POST['title']) && $_POST['title'] != '')? sanitize($_POST['title']) : '');
          $brand = ((isset($_POST['brand']) && !empty($_POST['brand']))? sanitize($_POST['brand']) : '');
          $parent = ((isset($_POST['parent']) && !empty($_POST['parent']))? sanitize($_POST['parent']) : '');
          $category = ((isset($_POST['child']) && !empty($_POST['child']))? sanitize($_POST['child']) : '');
          $price = ((isset($_POST['price']) && !empty($_POST['price']))? sanitize($_POST['price']) : '');
          $list_price = ((isset($_POST['list_price']) && !empty($_POST['list_price']))? sanitize($_POST['list_price']) : '');
          $description = ((isset($_POST['description']) && !empty($_POST['description']))? sanitize($_POST['description']) : '');
          $sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')? sanitize($_POST['sizes']) : '');
          $sizes = rtrim($sizes,',');
          $savedPhoto = '';

          if(isset($_GET['edit'])){
               $edit_id = (int)$_GET['edit'];
               $productResults = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
               $product = mysqli_fetch_assoc($productResults);
               if(isset($_GET['delete_image'])){
                    $image_url = $_SERVER['DOCUMENT_ROOT'].$product['imge'];
                    unlink($image_url);
                    $db->query("UPDATE products SET imge = '' WHERE id = '$edit_id'");
                    header('Location: products.php?edit='.$edit_id);
               }
               $category = ((isset($_POST['child']) && $_POST['child'] != '')? sanitize($_POST['child']) : $product['categories']);
               $title = ((isset($_POST['title']) && $_POST['title'] != '')? sanitize($_POST['title']) : $product['title']);
               $brand = ((isset($_POST['brand']) && $_POST['brand'] != '')? sanitize($_POST['brand']) : $product['brand']);
               
               $parentQ = $db->query("SELECT * FROM categories WHERE id = '$category'");
               $parentResult = mysqli_fetch_assoc($parentQ);
               $parent = ((isset($_POST['parent']) && $_POST['parent'] != '')? sanitize($_POST['parent']) : $parentResult['parent']);
               $price = ((isset($_POST['price']) && $_POST['price'] != '')? sanitize($_POST['price']) : $product['price']);
               $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')? sanitize($_POST['list_price']) : $product['list_price']);
               $description = ((isset($_POST['description']) && $_POST['description'] != '')? sanitize($_POST['description']) : $product['description']);
               $sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')? sanitize($_POST['sizes']) : $product['sizes']);
               $sizes = rtrim($sizes,',');
               $savedPhoto = (($product['imge'] != '')?$product['imge'] : '');
               $dbPath = $savedPhoto;
          }

          if(!empty($sizes)){
                    $sizeString = sanitize($sizes);
                    $sizeString = rtrim($sizeString,',');
                    $sizesArray = explode(',',$sizeString);
                    $sArray = array();
                    $qArray = array();
                    foreach($sizesArray as $ss){
                         $s = explode(':',$ss);
                         $sArray[] = $s[0];
                         $qArray[] = $s[1];
                    }
          }else{
               $sizesArray = array();
          }

          if($_POST){


               $errors = array();
               
               $required = array('title', 'brand', 'price', 'parent', 'child','sizes');
               foreach($required as $field){
                    if($_POST[$field] == ''){
                         $errors[] = 'All field with an asterisk must be filled.';
                         break;
                    }
               }

               
               if(!empty($_FILES)){
                    var_dump($_FILES);
                    $photo = $_FILES['photo'];
                    $name = $photo['name'];
                    $nameArray = explode('.',$name);
                    $fileName = $nameArray['photo'];
                    $fileExt = $nameArray[1];
                    
                    $mime = explode('/',$photo['type']);
                    $mimeType = $mime[0];
                    $mimeExt = $mime[1];

                    $tmpLoc = $photo['tmp_name'];
                    $fileSize = $photo['size'];

                    $allowed = array('png','jpg','jpeg','gif');

                    $uploadName = md5(microtime()).'.'.$fileExt;
                    $uploadPath = BASEURL.'images/products/'.$uploadName;
                    $dbPath = '/images/products/'.$uploadName;


                    if($mimeType != 'image'){
                         $errors[] = 'The file must be an image.';
                    }
                    if(!in_array($fileExt, $allowed)){
                         $errors[] = 'The file extension is not valid.';
                    }
                    if($fileSize > 5000000){
                         $errors[] = 'The file must be under 5mb.';
                    }
                    if($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')){
                         $errors[] = 'File extension does not match the file.';
                    }
               }
               
               if(!empty($errors)){
                    echo display_errors($errors);
               }else{
                    if(!empty($_FILES)){
                         move_uploaded_file($tmpLoc,$uploadPath);
                    }
                    $insertSQL = "INSERT INTO products(`title`, `price`, `list_price`, `brand`, `categories`, `imge`, `description`, `sizes`) 
                    VALUES ('$title', '$price', '$list_price', '$brand', '$category', '$dbPath', '$description', '$sizes')";
                    if(isset($_GET['edit'])){
                         $insertSQL = "UPDATE products SET title = '$title', price='$price', list_price = '$list_price', brand = '$brand', categories = '$category' , sizes = '$sizes' , imge = '$dbPath', description = '$description' WHERE id = '$edit_id'";
                    }
                    $db->query($insertSQL);
                    header('Location: products.php');
               }
          }
          
          
?>
     <h2 class="text-center"><?= ((isset($_GET['edit']))?'Edit a' : 'Add a new'); ?> product</h2>
     <form action="products.php?<?= ((isset($_GET['edit']))?'edit='.$edit_id : 'add=1'); ?>" method="POST" enctype="multipart/form-data">
          <div class="row">
               <div class="form-group col-md-3">
                    <label for="title">Title*:</label>
                    <input type="text" name="title" class="form-control" id="title" value="<?= $title; ?>">
               </div>
               <div class="form-group col-md-3">
                    <label for="brand">Brand*:</label>
                    <select class="form-control" name="brand" id="brand">
                         <option value="" <?= (($brand == '')? ' selected' : ''); ?>></option>
                         <?php while($b = mysqli_fetch_assoc($brandQUERY)) : ?>
                         <option value="<?= $b['id']; ?>" <?= (($brand == $b['id'])? ' selected' : ''); ?>><?= $b['brand']; ?></option>
                         <?php endwhile; ?>
                    </select>
               </div>
               <div class="form-group col-md-3">
                    <label for="parent">Parent category*:</label>
                    <select class="form-control" name="parent" id="parent">
                         <option value="" <?= (($parent == '')? ' selected' : ''); ?>></option>
                         <?php while($p = mysqli_fetch_assoc($parentQUERY)) : ?>
                         <option value="<?= $p['id']; ?>" <?= (($parent == $p['id'])? ' selected' : ''); ?>><?= $p['category']; ?></option>
                         <?php endwhile; ?>
                    </select>
               </div>
               <div class="form-group col-md-3">
                    <label for="child">Child category*:</label>
                    <select id="child" name="child" class="form-control">
                    </select>
               </div>
               <div class="form-group col-md-3">
                    <label for="price">Price*:</label>
                    <input type="text" id="price" name="price" class="form-control" value="<?= $price; ?>"> 
               </div>
               <div class="form-group col-md-3">
                    <label for="list_price">List price:</label>
                    <input type="text" id="list_price" name="list_price" class="form-control" value="<?= $list_price ?>"> 
               </div>
               <div class="form-group col-md-3">
                    <label>Quantity & Sizes*:</label>
                    <button class="btn btn-success" onclick="jQuery('#sizesModal').modal('toggle'); return false;">Quantity & Sizes</button>
               </div>
               
               <div class="form-group col-md-3">
                    <label for="sizes">Sizes & Quantity preview</label>
                    <input class="form-control" type="text" name="sizes" id="sizes" value="<?= $sizes; ?>" readonly>
               </div>

               <div class="form-group col-md-6">
                    <?php if($savedPhoto != '') :?>
                         <div class="savedPhoto">
                              <img src="<?= $savedPhoto ?>" />
                              <a href="products.php?delete_image=1&edit=<?= $edit_id; ?>" class="text-danger">Delete image</a>
                         </div>
                    <?php else:?>
                         <label for="photo">Product image:</label>
                         <input class="form-control" type="file" name="photo" id="photo">
                    <?php endif; ?>
               </div>
               <div class="form-group col-md-6">
                    <label for="description">Product description:</label>
                    <textarea class="form-control" type="text" name="description" id="description" rows="6"><?= $description;?></textarea>
               </div>
               
               <div class="form-group pull-right">
                    <a href="products.php" class="btn btn-default">Cancel</a>
                    <input type="submit" value="<?= ((isset($_GET['edit']))?'Edit' : 'Add'); ?>" class="btn btn-success">
               </div>
          </div>
     </form>


     <div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel">
          <div class="modal-dialog modal-lg" role="document">
               <div class="modal-content">
                    <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                         <h4 class="modal-title" id="sizesModalLabel">Size & Quantity</h4>
                    </div>
                    <div class="modal-body">
                         <div class="container-fluid">
                              <div class="row">
                              <?php for($i = 1; $i <= 12; $i++) : ?>
                                   
                                        <div class="form-group col-md-4">
                                             <label for="size<?=$i;?>">Size:</label>
                                             <input class="form-control" type="text" name="size<?=$i;?>" id="size<?=$i;?>" value="<?= ((!empty($sArray[$i-1]))? $sArray[$i - 1] : ''); ?>">
                                        </div>
                                        <div class="form-group col-md-2">
                                             <label for="quantity<?=$i;?>">Quantity: </label>
                                             <input class="form-control" type="number" name="quantity<?=$i;?>" id="quantity<?=$i;?>" value="<?= ((!empty($qArray[$i-1]))? $qArray[$i - 1] : ''); ?>" min="0">
                                        </div>
                              <?php endfor; ?>
                              </div>
                         </div>
                    </div>
                    <div class="modal-footer">
                         <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                         <button type="button" class="btn btn-primary" onclick="updateSizes(); jQuery('#sizesModal').modal('toggle'); return false;">Save changes</button>
                    </div>
               </div>
          </div>
     </div>


<?php
     }else{

          $sql = "SELECT * FROM products WHERE deleted != 1";
          $Presult = $db->query($sql);
          
          if(isset($_GET['featured'])){
               $id = (int)$_GET['id'];
               $featured = (int)$_GET['featured'];
               $SQLfeature = "UPDATE products SET featured = '$featured' WHERE id = '$id'";
               $db->query($SQLfeature);
               header('Location: products.php');
          }
?>
<h2 class="text-center">Products</h2>

<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add product</a>

<table class="table table-bordered table-condensed table-striped">
     <thead>
          <th></th>
          <th>Product</th>
          <th>Price</th>
          <th>Category</th>
          <th>Featured</th>
          <th>Sold</th>
     </thead>
     <tbody>
          <?php while($product = mysqli_fetch_assoc($Presult)) : 
               $childID = $product['categories'];
               $catSQL = "SELECT * FROM categories WHERE id = '$childID'";
               $result = $db->query($catSQL);
               $child = mysqli_fetch_assoc($result);
               $parentID = $child['parent'];
               $pSQL = "SELECT * FROM categories WHERE id = '$parentID'";
               $PARENTresult = $db->query($pSQL);
               $parent = mysqli_fetch_assoc($PARENTresult);
               $category = $parent['category'] . ' - ' . $child['category'];
          ?>
               <tr>
                    <td>
                         <a href="products.php?edit=<?= $product['id']; ?>" class="btn btn-outline-primary">
                              <span class="fa fa-edit"></span>
                         </a>
                          <a href="products.php?delete=<?= $product['id']; ?>" class="btn btn-outline-primary">
                              <span class="fa fa-trash"></span>
                         </a>
                    </td>
                    <td><?= $product['title']; ?></td>
                    <td><?= money($product['price']); ?></td>
                    <td><?= $category; ?></td>
                    <td>
                         <a href="products.php?featured=<?= (($product['featured'] == 0)?'1':'0'); ?>&id=<?= $product['id']; ?>" class="btn btn-outline-primary">
                              <span class="fa fa-<?= (($product['featured'])? 'minus' : 'plus'); ?>"></span>
                         </a>
                         &nbsp 
                         <?= (($product['featured'] == 1)? 'Featured' : 'Not featured'); ?>
                    </td>
                    <td>to do (0)</td>
               </tr>
          <?php endwhile; ?>
     </tbody>
</table>
<?php 
     } 
     include 'includes/footer.php';
?>
<script>
     jQuery('document').ready(function(){
          get_child_options('<?= $category; ?>');
     })
</script>
