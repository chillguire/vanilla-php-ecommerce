<?php
     define('BASEURL',$_SERVER['DOCUMENT_ROOT'].'/');
     define('CART_COOKIE','ChAvEzEsTa100MuErTo');
     define('CART_COOKIE_EXPIRE',time() + (86400 * 30));

     define('TAXRATE',0.087);
     define('CURRENCY','usd');
     define('CHECKOUTMODE','TEST'); // TEST to LIVE when going to use
     if(CHECKOUTMODE == 'TEST'){
          define('STRIPE_PRIVATE','sk_test_vwOeCCrn5hoG7X5sMNHBOlxh00NXZbVNpA');
          define('STRIPE_PUBLIC','pk_test_Zz6ivTKoqKbbAHodBVL4Suue008Uu6t9yI');
     }
     if(CHECKOUTMODE == 'LIVE'){
          define('STRIPE_PRIVATE','');
          define('STRIPE_PUBLIC','');
     }

?>