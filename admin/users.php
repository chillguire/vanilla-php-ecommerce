<?php 
     require_once '../core/init.php';
     if(!is_logged_in()){
          login_error_redirect();
     }
     if(!has_permission('admin')){
          permission_error_redirect('index.php');
     }
     include 'includes/head.php';
     include 'includes/navigation.php';

     if (isset($_GET['delete'])){
          $delete_id = sanitize($_GET['delete']);
          $db->query("DELETE FROM users WHERE id = '$delete_id'");
          $_SESSION['success_flash'] = 'User has been deleted.';
          header('Location: users.php');
     }
     if (isset($_GET['add'])){
          $name = ((isset($_POST['name']))?sanitize($_POST['name']) : '');
          $email = ((isset($_POST['email']))?sanitize($_POST['email']) : '');
          $password = ((isset($_POST['password']))?sanitize($_POST['password']) : '');
          $confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']) : '');
          $permissions = ((isset($_POST['permissions']))?sanitize($_POST['permissions']) : '');

          $errors = array();
          if($_POST){
               $emailQUERY = $db->query("SELECT * FROM users WHERE email = '$email'");
               $emailCount = mysqli_num_rows($emailQUERY);

               if($emailCount != 0){
                    $errors[] = 'That email is already taken.';
               }

               $required = array('name', 'email', 'password', 'confirm', 'permissions');
               foreach($required as $f){
                    if($_POST[$f] == ''){
                         $errors[] = 'You must fill out all fields.';
                         break;
                    }
               }

               if(strlen($password) < 6 ){
                    $errors[] = 'Password must be at least 6 characters.';
               }
               
               if($password != $confirm){
                    $errors[] = 'The typed passwords do not match.';
               }

               if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                    $errors[] = 'You must enter a valid email.';
               }



               if(!empty($errors)){
                    echo display_errors($errors);
               }else{
                    $hashed = password_hash($password,PASSWORD_DEFAULT);
                    $db->query("INSERT INTO users (`full_name`,`email`,`password`,`permissions`) 
                    VALUES('$name','$email','$hashed','$permissions')");
                    $_SESSION['success_flash'] = 'User has been added.';
                    header('Location: users.php');
               }
          }
     ?>
     
     <h2 class="text-center">Add new user</h2>
     <form action="users.php?<?= ((isset($_GET['edit']))?'edit='.$edit_id : 'add=1'); ?>" method="post">
          <div class="row">
               <div class="form-group col-md-6">
                    <label for="name">Full name:</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?=$name;?>">
               </div>
               <div class="form-group col-md-6">
                    <label for="email">Email:</label>
                    <input type="text" name="email" id="email" class="form-control" value="<?=$email;?>">
               </div>
               <div class="form-group col-md-6">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
               </div>
               <div class="form-group col-md-6">
                    <label for="confirm">Confirm password:</label>
                    <input type="password" name="confirm" id="confirm" class="form-control" value="<?=$confirm;?>">
               </div>
               <div class="form-group col-md-6">
                    <label for="permissions">Permissions:</label>
                    <select class="form-control" name="permissions">
                         <option value=""<?= (($permissions == '')? ' selected': ''); ?>></option>
                         <option value="editor"<?= (($permissions == 'editor')? ' selected': ''); ?>>Editor</option>
                         <option value="admin,editor"<?= (($permissions == 'admin,editor')? ' selected': ''); ?>>Admin</option>
                    </select>
               </div>
               <div class="form-group col-md-6 text-right">
                    <a href="users.php" class="btn btn-default">Cancel</a>
                    <input type="submit" value="<?= ((isset($_GET['edit']))?'Edit' : 'Add'); ?>" class="btn btn-success">
               </div>
          </div>
     </form>
     
     <?php
     }else{
     $userQuery = $db->query("SELECT * FROM users ORDER BY full_name");
?>

<h2>Users</h2>
<a href="users.php?add=1" class="btn btn-success pull-right">Add new user</a>
<table class="table table-bordered table-striped table-condensed">
     <thead>
          <th></th>
          <th>Name</th>
          <th>Email</th>
          <th>Join date</th>
          <th>Last login</th>
          <th>Permissions</th>
     </thead>
     <tbody>
          <?php while($user = mysqli_fetch_assoc($userQuery)) : ?>
          <tr>
               <td>
               <?php if($user['id'] != $user_data['id']) : ?>
               <a href="users.php?delete=<?= $user['id']; ?>" class="btn btn-outline-primary">
                    <span class="fa fa-trash"></span>
               </a>
          <?php endif; ?>
               </td>
               <td><?= $user['full_name']; ?></td>
               <td><?= $user['email']; ?></td>
               <td><?= pretty_date($user['join_date']); ?></td>
               <td><?=(($user['last_login'] == '')? 'Never': pretty_date($user['last_login'])); ?></td>
               <td><?= $user['permissions']; ?></td>
          </tr>
          <?php endwhile; ?>
     </tbody>
</table>


<?php 
     }
     include 'includes/footer.php';
?>