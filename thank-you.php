<?php 
     require_once 'core/init.php'; 

     // Set your secret key: remember to change this to your live secret key in production
     // See your keys here: https://dashboard.stripe.com/account/apikeys
     \Stripe\Stripe::setApiKey(STRIPE_PRIVATE);

     // Token is created using Checkout or Elements!
     // Get the payment token ID submitted by the form:
     $token = $_POST['stripeToken'];

     $full_name = sanitize($_POST['full_name']);
     $email = sanitize($_POST['email']);
     $street = sanitize($_POST['street']);
     $street2 = sanitize($_POST['street2']);
     $city = sanitize($_POST['city']);
     $state = sanitize($_POST['state']);
     $zip_code = sanitize($_POST['zip_code']);
     $country = sanitize($_POST['country']);
     $tax = sanitize($_POST['tax']);
     $city = sanitize($_POST['sub_total']);
     $grand_total = sanitize($_POST['grand_total']);
     $cart_id = sanitize($_POST['cart_id']);
     $description = sanitize($_POST['description']);
     $charge_amount = number_format($grand_total,2) * 100;
     $metadata = array(
          "cart_id"      => $cart_id,
          "tax"          => $tax,
          "sub_total"    => $sub_total,
     );

     // aqui esta el error.

     $charge = \Stripe\Charge::create([
     'amount' => $charge_amount,
     'currency' => CURRENCY,
     'description' => $description,
     'receipt_email' => $email,
     'metadata' => $metadata,
     'description' => $description,
     'description' => $description,
     'source' => $token,
     ]);

     $db->query("UPDATE cart SET paid = 1 WHERE id = '{$cart_id}'");
     $db->query("INSERT INTO transactions
     (charge_id,cart_id,full_name,email,street,street2,city,`state`,zip_code,country,sub_total,tax,grand_total,`description`,txn_type) 
     VALUES
     ('$charge->id','$cart_id','$full_name','$email','$street','$street2','$city','$state','$zip_code','$country','$sub_total','$tax','$grand_total','$description','falla esto?')");

     $domain = (($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false);
     setcookie(CART_COOKIE,'',"/",$domain,false);
     include 'includes/head.php';
     include 'includes/navigation.php';
     include 'includes/header-partial.php';
?>
     <h1 class="text-center text-success">Thank you!</h1>
     <p>Your cart has been successfully charged <?= money($grand_total) ?>. 
     You have been emailed a reciept. Look it up and print this page as a receipt.</p>
     <p>Your receipt number is <strong><?= $cart_id ?></strong></p>
     <p>Your order will be shipped to the address below.</p>
     <address>
          <?= $full_name; ?>
          <?= $street; ?>
          <?= (($street2 != '')? $street2 : '' ); ?>
          <?= $city . ', ' . $state . ' ' . $zip_code; ?>
          <?= $country; ?>
     </address>
<?php
     include 'includes/footer.php';


?>