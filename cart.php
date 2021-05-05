<?php
require_once 'core/init.php'; 
include 'includes/head.php'; 
include 'includes/navigation.php'; 
include 'includes/header-partial.php';
if($cart_id != ''){
     $cartQ = $db->query("SELECT * FROM cart WHERE id = '{$cart_id}'");
     $result = mysqli_fetch_assoc($cartQ);
     $items = json_decode($result['items'],true);
     $i = 1;
     $sub_total = 0;
     $item_count = 0;
}
?>
<div class="col-md-12">
     <h2 class="text-center">My cart</h2>
     <div class="row">
          <?php if($cart_id == '') : ?>
          <div>
               <p class="text-center text-danger">
               Your cart is empty. 
               </p>
          </div>
          <?php else: ?>
               
          
               <div class="card shadow mb-12">
                    <div class="card-header py-3">
                         <h6 class="m-0 font-weight-bold text-primary">Check out</h6>
                    </div>
                    <div class="card-body">
                         <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                              <thead>
                                   <th>#</th>
                                   <th>Item</th>
                                   <th>Price</th>
                                   <th>Quantity</th>
                                   <th>Size</th>
                                   <th>Sub-total</th>
                              </thead>
                              <tbody>
                                   <?php foreach($items as $item){
                                        $product_id = $item['id'];
                                        $productQ = $db->query("SELECT * FROM products WHERE id = '{$product_id}'");
                                        $product = mysqli_fetch_assoc($productQ);
                                        $sArray = explode(',',$product['sizes']);
                                        foreach($sArray as $sizeString){
                                             $s =explode(':',$sizeString);
                                             if($s[0] == $item['size']){
                                                  $available = $s[1];
          
                                             }
                                        }
                                        ?>
          
                                        <tr>
                                             <td><?= $i; ?></td>
                                             <td><?= $product['title']; ?></td>
                                             <td><?= money($product['price']); ?></td>
                                             <td>
                                                  <button class="btn btn-sm btn-outline-primary" onclick="update_cart('remove','<?= $product['id']?>','<?= $item['size']?>')">
                                                       <span class="fa fa-minus"></span>
                                                  </button>
                                                  <?= $item['quantity']; ?>
                                                  <?php if($item['quantity'] < $available) : ?>
                                                  <button class="btn btn-sm btn-outline-primary" onclick="update_cart('add','<?= $product['id']?>','<?= $item['size']?>')">
                                                       <span class="fa fa-plus"></span>
                                                  </button>
                                                  
                                                  <?php else: ?>
                                                  <button class="btn btn-sm btn-outline-danger">
                                                       <span class="fa fa-exclamation-triangle"></span>
                                                  </button>
                                                  <?php endif; ?>
                                             </td>
                                             <td><?= $item['size']; ?></td>
                                             <td><?= money($item['quantity'] * $product['price']); ?></td>
                                        </tr>
          
                                        <?php
                                             $i++;
                                             $item_count += $item['quantity'];
                                             $sub_total += ($item['quantity'] * $product['price']);
                                             }
                                             $tax = TAXRATE * $sub_total;
                                             $tax = number_format($tax,2);
                                             $grandTotal = $tax + $sub_total;
                                        ?>
          
                              </tbody>
                         </table>
                    </div>
               </div>
               
               
               <div class="card shadow mb-12">
                    <div class="card-header py-3">
                         <h6 class="m-0 font-weight-bold text-primary">Total</h6>
                    </div>
                    <div class="card-body">
                         <table class="table table-bordered table-condensed text-right">
                              <thead class="totals-table-header">
                                   <th>Total items</th>
                                   <th>Sub-total</th>
                                   <th>Tax</th>
                                   <th>Grand total</th>
                              </thead>
                              <tbody>
                                   <tr>
                                        <td><?= $item_count; ?></td>
                                        <td><?= money($sub_total); ?></td>
                                        <td><?= money($tax); ?></td>
                                        <td class="bg-success"><?= money($grandTotal); ?></td>
                                   </tr>
                              </tbody>
                         </table>
                    </div>
               </div>

               <!-- Button trigger modal -->
               <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#checkoutModal">
               <span class="fa fa-shopping-cart"></span>
               Checkout
               </button>

               <!-- Modal -->
               <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
               <div class="modal-dialog modal-lg" role="document">
               <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="checkoutModalLabel">Shipping address</h4>
                    </div>
                    <div class="modal-body">
                         
                                     <!-- FORMULARIO DE COMPRA (CHECKOUT) -->
                                     
                                        <form action="thank-you.php" class="payment-form" method="post">
                                             <span id="payment-errors"></span>
                                             <input type="hidden" name="tax" value="<?= $tax; ?>">
                                             <input type="hidden" name="sub_total" value="<?= $sub_total; ?>">
                                             <input type="hidden" name="grand_total" value="<?= $grand_total; ?>">
                                             <input type="hidden" name="cart_id" value="<?= $cart_id; ?>">
                                             <input type="hidden" name="description" value="<?= $description; ?>">
                                             <input type="hidden" name="grand_total" value="<?= $item_count . ' item' . (($item_count > 1)? 's' : '' ) . ' from the store.'; ?>">

                                             <div id="step1" class="form-group" style="display:block;">
                                                  <div class="row">
                                                       <div class="form-group col-md-6">
                                                            <label for="full_name">Full name:</label>
                                                            <input type="text" class="form-control" id="full_name" name="full_name">
                                                       </div>
                                                       <div class="form-group col-md-6">
                                                            <label for="email">E-mail:</label>
                                                            <input type="email" class="form-control" id="email" name="email">
                                                       </div>
                                                       <div class="form-group col-md-6">
                                                            <label for="street">Street address:</label>
                                                            <input type="text" class="form-control" id="street" name="street" data-stripe="address_line1">
                                                       </div>
                                                       <div class="form-group col-md-6">
                                                            <label for="street2">Street address 2:</label>
                                                            <input type="text" class="form-control" id="street2" name="street2" data-stripe="address_line2">
                                                       </div>
                                                       <div class="form-group col-md-6">
                                                            <label for="city">City:</label>
                                                            <input type="text" class="form-control" id="city" name="city" data-stripe="address_city">
                                                       </div>
                                                       <div class="form-group col-md-6">
                                                            <label for="state">State:</label>
                                                            <input type="text" class="form-control" id="state" name="state" data-stripe="address_state">
                                                       </div>
                                                       <div class="form-group col-md-6">
                                                            <label for="zip_code">ZIP code:</label>
                                                            <input type="text" class="form-control" id="zip_code" name="zip_code" data-stripe="address_zip">
                                                       </div>
                                                       <div class="form-group col-md-6">
                                                            <label for="country">Country:</label>
                                                            <input type="text" class="form-control" id="country" name="country" data-stripe="address_country">
                                                       </div>
                                                  </div>
               
                                             </div>
                                             <div id="step2" class="form-group" style="display:none;">
                                                  <div class="row">
                                                       <div class="form-group col-md-3">
                                                            <label for="name">Name on card:</label>
                                                            <input type="text" id="name" class="form-control" data-stripe="name">
                                                       </div>
                                                       <div class="form-group col-md-3">
                                                            <label for="number">Card number:</label>
                                                            <input type="text" id="number" class="form-control" data-stripe="cardNumber">
                                                       </div>
                                                       <div class="form-group col-md-2">
                                                            <label for="cvc">CVC:</label>
                                                            <input type="text" id="cvc" class="form-control" data-stripe="cardCvc">
                                                       </div>
                                                       <div class="form-group col-md-2">
                                                            <label for="exp-month">Expire month:</label>
                                                            <select id="exp-month" class="form-control" data-stripe="exp-month">
                                                                 <option value=""></option>
                                                                 <?php for($i = 1; $i < 13; $i++) : ?>
                                                                      <option value="<?= $i; ?>"><?= $i; ?></option>
                                                                 <?php endfor; ?>
                                                            </select>
                                                       </div>
                                                       <div class="form-group col-md-2">
                                                            <label for="exp-year">Expire year:</label>
                                                            <select id="exp-year" class="form-control" data-stripe="exp-year">
                                                                 <option value=""></option>
                                                                 <?php $yr = date("Y"); for($i = 0; $i < 11; $i++) : ?>
                                                                      <option value="<?= $yr + $i; ?>"><?= $yr + $i; ?></option>
                                                                 <?php endfor; ?>
                                                            </select>
                                                       </div>
                                                  </div>
                                             </div>
     
                    

                    </div>
                    <div class="modal-footer">
                         <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                         <button type="button" class="btn btn-primary" onclick="check_address();" id="next_button">Next</button>
                         <button type="button" class="btn btn-primary" onclick="back_address();" id="back_button" style="display:none;">Back</button>
                         <button type="submit" class="btn btn-primary" id="check_out_button" style="display:none;">Buy</button>
                    </div>
                </form>
               </div>
               </div>
               </div>

          <?php endif; ?>
     </div>
</div>

<script>


     function back_address(){
          jQuery('#payment-errors').html("");
          jQuery('#step1').css("display" , "block");
          jQuery('#step2').css("display" , "none");
          jQuery('#next_button').css("display" , "inline-block");
          jQuery('#back_button').css("display" , "none");
          jQuery('#check_out_button').css("display" , "none");
          jQuery('#checkoutModalLabel').html('Shipping address');
     }
     function check_address(){
          var data = {
               'full_name' : jQuery('#full_name').val(),
               'email' : jQuery('#email').val(),
               'street' : jQuery('#street').val(),
               'street2' : jQuery('#street2').val(),
               'city' : jQuery('#city').val(),
               'state' : jQuery('#state').val(),
               'zip_code' : jQuery('#zip_code').val(),
               'country' : jQuery('#country').val(),
          };
          jQuery.ajax({
               url: '/admin/parsers/check_address.php',
               method: "post",
               data: data,
               success: function(data){
                    if(data != 'passed'){
                         jQuery('#payment-errors').html(data);
                    }
                    if(data == 'passed'){
                         jQuery('#payment-errors').html("");
                         jQuery('#step1').css("display" , "none");
                         jQuery('#step2').css("display" , "block");
                         jQuery('#next_button').css("display" , "none");
                         jQuery('#back_button').css("display" , "inline-block");
                         jQuery('#check_out_button').css("display" , "inline-block");
                         jQuery('#checkoutModalLabel').html('Card details');
                    }
               },
               error: function(){
                    alert("Something went wrong.");
               }
          });
     }

     Stripe.setPublishableKey('<?= STRIPE_PUBLIC ?>');

     function stripeTokenHandler(token) {
     // Insert the token ID into the form so it gets submitted to the server
          var form = document.getElementById('payment-form');
          var hiddenInput = document.createElement('input');
          hiddenInput.setAttribute('type', 'hidden');
          hiddenInput.setAttribute('name', 'stripeToken');
          hiddenInput.setAttribute('value', token.id);
          form.appendChild(hiddenInput);

          // Submit the form
          form.submit();
     }
     
     
     // Create a token or display an error when the form is submitted.
     var form = document.getElementById('payment-form');
     form.addEventListener('submit', function(event) {
          event.preventDefault();

          stripe.createToken(card).then(function(result) {
               if (result.error) {
                    // Inform the customer that there was an error.
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
               } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
               }
          });
     });

   
</script>
<?php 
include 'includes/footer.php';  
?>