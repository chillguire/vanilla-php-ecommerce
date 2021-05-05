<?php
require_once('core/init.php'); 
require 'includes/head.php'; 
require 'includes/navigation.php'; 
require 'includes/header-full.php';
require 'includes/left-bar.php';

     $sql = "SELECT * FROM products WHERE featured = 1 AND deleted = 0";
     $featured = $db->query($sql);
?>
<!-- ************ -->
<!-- MAIN CONTENT -->
<!-- ************ -->
     <div class="col-md-8">
          <h2 class="text-center">Featured products</h2>
          <div class="row">
               <?php while($product = mysqli_fetch_assoc($featured)) : ?>
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