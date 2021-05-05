<?php
require_once 'core/init.php'; 
include 'includes/head.php'; 
include 'includes/navigation.php'; 
include 'includes/header-partial.php';
include 'includes/left-bar.php';

     if(isset($_GET['cat'])){
          $cat_id = sanitize($_GET['cat']);
     }else{
          $cat_id = '';
     }

     $sql = "SELECT * FROM products WHERE categories = '$cat_id' AND deleted = 0";
     $productQ = $db->query($sql);
     $category = get_category($cat_id);
?>
<!-- ************ -->
<!-- MAIN CONTENT -->
<!-- ************ -->
     <div class="col-md-8">
          <h2 class="text-center"><?= $category['parent'] . ' > ' . $category['child']; ?></h2>
          <div class="row">
               <?php while($product = mysqli_fetch_assoc($productQ)) : ?>
               <div class="col-md-3">
                    <h4><?= $product['title']; ?></h4>
                    <img src="<?= $product['imge']; ?>" alt="" class="img-thumb">
                    <p class="list-price text-danger"> List price: <s>$<?= $product['list_price']; ?></s></p>
                    <p class="price">Our price: $<?= $product['price']; ?></p>
                    <button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?= $product['id']; ?>)">Details</button>
               </div>
               <?php endwhile; ?>     
          </div>   
     </div>

<?php 
// include 'includes/details-modal.php';
include 'includes/right-bar.php';
include 'includes/footer.php';  
?>