     <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <a href="/admin/index.php" class="navbar-brand">Introspective Admin</a>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
               <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                         <a href="brands.php" class="nav-link d">Brands</a>
                    </li >
                    <li class="nav-item dropdown">
                         <a href="categories.php" class="nav-link ">Categories</a>
                    </li>
                    <li class="nav-item dropdown">
                         <a href="products.php" class="nav-link ">Products</a>
                    </li>
                    <?php if(has_permission('admin')) : ?>
                         <li class="nav-item dropdown">
                              <a href="users.php" class="nav-link ">Users</a>
                         </li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                         <a href="#" class="nav-link dropdown-toggle" id="navbarDropdown"  role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Hello, <?= $user_data['first']?></a>
                         <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                              <a href="change_password.php" class="dropdown-item">Change password</a>
                              <a href="logout.php" class="dropdown-item">Log out</a>
                         </div>
                    </li>
               </ul>
          </div>
     </nav>