<?php
     $sql = "SELECT * FROM categories WHERE parent = 0";
     $pquery = $db->query($sql);
?>
     <!-- ****** -->
     <!-- NAV BAR-->
     <!-- ****** -->

     <nav class="navbar navbar-expand-lg navbar-light bg-light">
               <a href="index.php" class="navbar-brand">Introspective</a>
               <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
               </button>
               <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                    <!-- Parent menu items -->
                    <?php while ($parent = mysqli_fetch_assoc($pquery)) : ?>
                          <?php 
                          $parent_id = $parent['id'];
                          $sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
                          $cquery = $db->query($sql2);
                          ?>
                         <li class="nav-item dropdown">
                              <a href="category.php?cat=<?= $parent['id']; ?>" class="nav-link dropdown-toggle" id="navbarDropdown"  role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <?php echo $parent['category']; ?>
                              </a>
                              <div class="dropdown-menu dropdown-menu-left animated--grow-in" aria-labelledby="navbarDropdown">
                                   <?php while ($child = mysqli_fetch_assoc($cquery)) : ?>
                                   <a href="category.php?cat=<?= $child['id']; ?>" class="dropdown-item"><?php echo $child['category']; ?></a>
                                   <?php endwhile; ?>
                              </div>
                         </li>
                    <?php endwhile; ?>
                         <li class="nav-item dropdown" >
                              <a href="cart.php" class="nav-link">
                                   <span class="fa fa-shopping-cart"></span>
                                   My cart
                              </a>  
                         </li>
                    </ul>
               </div>
     </nav>