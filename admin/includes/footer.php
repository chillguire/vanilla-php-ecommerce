          </div>
</div>

     <!-- ****** -->
     <!-- FOOTER -->
     <!-- ****** -->
     <footer class="text-center" id="footer">
          &copy; Copyright 2019 Introspective
     </footer>    

<script>
     function updateSizes(){
          var sizeString = '';
          for(var i=1;i<=12;i++){
               if(jQuery('#size' + i).val() != '' && jQuery('#quantity' + i).val() != ''){
                    sizeString += jQuery('#size' + i).val()+':'+jQuery('#quantity'+i).val()+',';

               }
          }
          jQuery('#sizes').val(sizeString);
     }


     function get_child_options(selected){
          // grab parent id
          if(typeof selected === 'undefined'){
               var selected = '';
          }
          var parentID = jQuery('#parent').val();
          data = {"parentID" : parentID, "selected" : selected };
          // AJAX REQUEST
          jQuery.ajax({
               url: '/admin/parsers/child_categories.php',
               type: 'POST',
               data: data,
               success: function(data){
                    jQuery('#child').html(data);
               },
               error: function(){alert("Something went wrong with the child options.")},
          });
     }
     // listener call
     jQuery('select[name="parent"]').change(function(){
          get_child_options();
     });
</script>

</body>
</html>