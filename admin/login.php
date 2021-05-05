<?php
     require_once '../core/init.php';
     include 'includes/head.php';

     $email = ((isset($_POST['email']))? sanitize($_POST['email']) : '');
     $email = trim($email);
     $password = ((isset($_POST['password']))? sanitize($_POST['password']) : '');
     $password = trim($password);
     $errors = array();

     // $password = 'password';
?>
<div id="login-form">
     <div>
     <?php 
          if($_POST){
               if(empty($_POST['email']) || empty($_POST['password'])){
                    $errors[] = 'You must provide email and password.';
               }

               if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                    $errors[] = 'You must enter a valid email.';
               }


               /*if(strlen($password) < 6 ){
                    $errors[] = 'Password must be at least 6 characters.';
               }*/
               
               $query = $db->query("SELECT * FROM users WHERE email = '$email'");
               $user = mysqli_fetch_assoc($query);
               $userCount = mysqli_num_rows($query);
               if($userCount < 1){
                    $errors[] = 'That email doesn\'t exist in our database.';
               }else{
                   if(!password_verify($password, $user['password'])){
                    $errors[] = 'The password does not match our records.';
                    } 
               }
             
               
               if (!empty($errors)){
                    echo display_errors($errors);
               }else{
                    $user_id = $user['id'];
                    login($user_id);
               }
          }
     ?>
     </div>
     <h2 class="text-centered">Login</h2>
     <form action="login.php" method="post">
          <div class="form-group">
               <label for="email">Email: </label>
               <input type="text" name="email" id="email" class="form-control" value="<?= $email ?>">
          </div>
          <div class="form-group">
               <label for="password">Password: </label>
               <input type="password" name="password" id="password" class="form-control" data-toggle="password" value="<?= $password ?>">
          </div>
          <div class="form-group">
               <input type="submit" class="btn btn-primary" value="Login">
          </div>
     </form>
     <p class="text-right"><a href="/index.php" alt="home">Visit site</a></p>
</div>

<?php
     include 'includes/footer.php';
?>