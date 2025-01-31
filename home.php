<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_SESSION['cart'])){
   
} else {
   $_SESSION['cart'] = [];
}

if(isset($_SESSION['fav'])){
   
} else {
   $_SESSION['fav'] = [];
}

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addTOcart'])){
   $product_id = $_POST['product_id'];
   $product_name = $_POST['name'];
   $product_price = $_POST['price'];
   $product_image = $_POST['image'];
   $product_quantity = $_POST['quantity'];

   $check_product_id = $conn->prepare("SELECT product_id FROM `cart` WHERE user_id = '$user_id'");
   $check_product_id->execute();
   

   $flag = true;

   while($fetch_product = $check_product_id->fetch(PDO::FETCH_ASSOC)){
      if (in_array($product_id, $fetch_product)){
         $flag = false;
         break;
      }
   };
   if($flag==true){
      if($user_id > 0){
      $send_to_cart = $conn->prepare("INSERT INTO `cart` (user_id , product_id , name , price , image , quantity)
                                    VALUES (? , ? , ? , ?, ? , ?)"); 
      $send_to_cart->execute([$user_id , $product_id , $product_name , $product_price, $product_image, $product_quantity]);

   }else {
      $array_cart = [$product_id , $product_name , $product_price, $product_image, $product_quantity];
      array_push($_SESSION['cart'], $array_cart);
      // echo'<pre>';
      // print_r($_SESSION['cart']);
      // echo'</pre>';
   }
}
}







if(isset($_POST['add_to_wishlist'])){

   if($user_id == ''){

      $flag = true;
      $pid = $_POST['product_id'];

      foreach($_SESSION['fav'] as $id){
         if (in_array($pid,$id)){
            $flag = false;
            break;
         }
      };
      if($flag==true){
         $array_fav = [$pid];
         array_push($_SESSION['fav'], $array_fav);
         // echo'<pre>';
         // print_r($_SESSION['fav']);
         // echo'</pre>';
      }

   }else{

      $pid = $_POST['product_id'];


      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `favorite` WHERE product_id = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$pid, $user_id]);

      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE product_id = ? AND user_id = ?");
      $check_cart_numbers->execute([$pid, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $message[] = 'Your Product <span style="color:red">Already</span> Added To Wishlist!';
      }elseif($check_cart_numbers->rowCount() > 0){
         $message[] = 'Your Product <span style="color:red">Already</span> Added To Cart!';
      }else{
         $insert_wishlist = $conn->prepare("INSERT INTO `favorite`(user_id, product_id) VALUES(?,?)");
         $insert_wishlist->execute([$user_id, $pid]);
         $message[] = 'Your Product <span style="color:green">Added</span> To Wishlist!';
      }

   }

}



?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="icon" type="image/x-icon" href="./images/logo.png">

   <!-- custom css file link  -->
   <!-- <link rel="stylesheet" href="css/style.css"> -->
<style> 
<?php 
include("css/style.css");

?>

</style>

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="home-bg">

   <section class="home">

      <div class="swiper home-slider">
      
         <div class="swiper-wrapper">

   <!-- بداية كود السلايدر لعرض الصور  -->
            <div class="swiper-slide slide" class="pic">
               <div class="content">
               <h1>Welcome In Art Hand Craft Website</h1>
               <h2>Creativity Without Limits</h2>
               <a href="shop.php" class="btn">Shop Now</a>
               </div>
               <div class="image">
               <img src="images/home1.png" alt="" style="height: 80vh;">
               </div>
            </div>

            <div class="swiper-slide slide">
               <div class="image">
                  <img src="./images/home2.png" alt="" style="height: 80vh;">
               </div>
               <div class="content">
                  <span style="font-size: 25px;">Botticelli-Map-of-the-Hell</ style="font-size: 25px;">
                  <h3>Rare Arts</h3>
                  <a href="https://www.artmajeur.com/gourdange-damien/en/artworks/12408437/psychedelic-shape-string-art" class="btn">Read More</a>
               </div>
            </div>
            <div class="swiper-slide slide">
               <div class="image">
                  <img src="./images/home.png" alt="" style="height: 80vh;">
               </div>
               <div class="content">
                  <span style="font-size: 25px;">Glowing-Sunset</span>
                  <h3>Popular Arts</h3>
                  <a href="https://www.saatchiart.com/print/Painting-Flowers-Small-bouquet-Acrylic-painting/981650/7120589/view" class="btn">Shop from outside</a>
               </div>
            </div>


         </div>

         <div class="swiper-pagination"></div>

      </div>

   </section>

</div>



<!-- start for category  -->

<section class="category">

   <h1 class="heading">shop by category</h1>

   <div class="swiper category-slider">

   <div class="swiper-wrapper">

   <a href="category.php?category=8" class="swiper-slide slide">
      <img src="images/resincateg.png" alt="">
      <h3>RESIN ART</h3>
   </a>

   <a href="category.php?category=6" class="swiper-slide slide">
      <img src="images/qullingCateg.png" alt="">
      <h3>QULLING ART</h3>
   </a>

   <a href="category.php?category=9" class="swiper-slide slide">
      <img src="images/stringcate.png" alt="">
      <h3>STRING ART</h3>
   </a>

   <a href="category.php?category=7" class="swiper-slide slide">
      <img src="images/acrylicCateg.png" alt="">
      <h3>ACRYLIC ART</h3>
   </a>

   </div>


   <div class="swiper-pagination"></div>

   </div>

</section>

<!-- start for product  -->

<section class="home-products">

   <h1 class="heading">Sales Product</h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   <?php
     $select_products = $conn->prepare("SELECT * FROM `products` WHERE is_sale='1'"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
         $i=0;
         $is_product_in_store = ($fetch_product['store']-$fetch_product['sold']);
         if ( $is_product_in_store <= 0 ){
            continue;
         } else { ?>
   <form action="" method="post" class="swiper-slide slide" style="height:430px">
      <input type="hidden" name="product_id" value="<?= $fetch_product['product_id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <?php 
      if ($fetch_product['is_sale'] == 1){
         ?>
         <input type="hidden" name="price" value="<?=$fetch_product['price_discount'];?>">
         <?php
      } else {
         ?>
         <input type="hidden" name="price" value="<?=$fetch_product['price'];?>">
         <?php
      }
      ?>
      <input type="hidden" name="image" value="<?= $fetch_product['image']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?pid=<?= $fetch_product['product_id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['image']; ?>" alt="">
      <div class="name"><?= $fetch_product['name']; ?></div>
      <?php $product_category = $conn->prepare("SELECT * 
                                        FROM `products`
                                        INNER JOIN `category` ON products.category_id = category.category_id");
                  $product_category->execute();
                  if($product_category->rowCount() > 0){
                     while($fetch_product_category = $product_category->fetch(PDO::FETCH_ASSOC)){ 
                        if($i==0 && $fetch_product['category_id'] == $fetch_product_category['category_id'] ){
                        $i++;
            ?>
                        <div class="details" style="color : rgb(133, 132, 132); font-size:15px"><span>Category : <?= $fetch_product_category['category_name']; ?></span>
      </div>
            <?php 
                        }
                     }
                  }
            ?>
      <div class="flex">

         <?php if ($fetch_product['is_sale'] == 1){ ?>

            <div class="price"><span><del style="text-decoration:line-through; color:silver">$<?= $fetch_product['price']; ?></del><ins style="color:#rgb(0, 0, 69) !important; padding:20px 0px"> $<?=$fetch_product['price_discount'];?></ins> </span></div>

         <?php } else { ?>

            <div class="name" style="color:rgb(0, 0, 69) !important;">$<?= $fetch_product['price']; ?></div> <?php } ?>

         <?php if (($fetch_product['store']-$fetch_product['sold']) != '1'){?>


            <input type="number" name="quantity" class="qty" min="1" max="<?=$fetch_product['store']-$fetch_product['sold'];?>" value="1">

         <?php } else { ?>
            <input type="hidden" name="quantity" value="1">
         <?php } ?> 

      </div>
      <button type="submit" class="btn" name="addTOcart">Add To Cart</button>
   </form>
   <?php
      } } }else{
      echo '<p class="empty">No Products Added Yet!</p>';
   }
   ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>









<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".home-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
    },
});

 var swiper = new Swiper(".category-slider", {
   loop:false,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
         slidesPerView: 2,
       },
      650: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 4,
      },
      1024: {
        slidesPerView: 4,
      },
   },
});

var swiper = new Swiper(".products-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      550: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>