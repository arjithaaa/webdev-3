<?php
include ("server.php");
if(!isset($_SESSION['id']))header("location: intro.php");
else if($_SESSION['type'] != "buyer")header("location: intro.php");
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <style>
    body{
      background-color: #eeeeee;
      padding-bottom: 6%;
    }
    nav{
      margin-bottom: 5%;
    }
    .arrange{
      display: flex;
      flex-wrap: wrap;
    }
    .arr-item{
      flex-basis: 33.333333%;
      padding: 2%;
    }
    </style>
  </head>
  <body>
    <nav class = "navbar navbar-expand-sm bg-light">
      <a href="intro.php" class="navbar-brand mr-auto ml-3 text-dark" style="font-size: 1.5rem; font-weight: bold;">KartMart</a>
      <ul class="navbar-nav ml-auto" style="font-size: 1.25rem;">
        <li class="nav-item mr-4 ml-4">
          <a class="text-dark" href="buyer.php?home=1" style="text-decoration: none;">Dashboard</a>
        </li>
        <li class="nav-item mr-4 ml-4 text-dark">
          <a class="text-dark" href="buyer.php?home=1#shop" style="text-decoration: none;">Shop now</a>
        </li>
        <li class="nav-item mr-4 ml-4 text-dark">
          <a class="text-dark" href="cart.php?view=1" style="text-decoration: none;">Your cart</a>
        </li>
        <li class="nav-item mr-4 ml-4 text-dark">
          <a class="text-dark" href="buyer.php?home=1#purchased" style="text-decoration: none;">Purchase history</a>
        </li>
        <li class="nav-item mr-4 ml-4 text-dark">
          <a class="btn btn-outline-dark btn-sm mr-4" href="intro.php?logout=1" role="button">Logout</a>
        </li>
      </ul>
    </nav>
    <div class="container p-3" id="purchased">
      <h2>Purchased By You</h2>
    </div>

    <div class="container arrange bg-light p-5" id="recent">
      <?php
      $i = 0;
      while($all_items = mysqli_fetch_assoc($result_pur)){
        $no = $all_items['item_id'];
        $q = $all_items['qty'];
        $query = "SELECT * FROM item WHERE item_id=?;";
        $stmt = mysqli_stmt_init($db);
        if (!mysqli_stmt_prepare($stmt, $query))
        {
            echo "FAILED here1";
        }
        else
        {
            mysqli_stmt_bind_param($stmt, "i", $no);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $view_pur = mysqli_fetch_assoc($result);
            echo "<div class='arr-item'>
              <div class='card h-75'>
                <img src='...' class='card-img-top' alt='...'>
                <div class='card-body'>
                  <h5 class='card-title'>{$view_pur['name']}</h5>
                  <p class='card-text'>{$view_pur['description']}<br>Price: {$view_pur['price']} Rs<br>Quantity: {$q}</p>
                </div>
              </div>
            </div>";
            $i = 1;
        }
      }
        if($i == 0)echo "<p class='text-dark'>You have not purchased any items yet!</p>";
        ?>
    </div>

    <div class="container p-3" id="shop">
      <h2>Shop now</h2>
    </div>

    <div class="container arrange bg-light p-5" id="recent">
      <?php $i = 0;
        while ($view_pur = mysqli_fetch_assoc($result_shop)){
          echo "<div class='arr-item'>
            <div class='card h-75'>
              <img src='...' class='card-img-top' alt='...'>
              <div class='card-body'>
                <h5 class='card-title'>{$view_pur['name']}</h5>
                <p class='card-text'>{$view_pur['description']}<br>Price: {$view_pur['price']} Rs<br>Quantity: {$view_pur['quantity']}</p>
                <form method='post' action='buyer.php?home=1&item_buy={$view_pur['item_id']}'>
                  <label for='qty'>Select quantity: </label>
                  <select id='qty' name='qty'>";

                    for($i = 1; $i<=$view_pur['quantity'];$i = $i + 1){
                      echo "<option value='{$i}'>{$i}</option>";
                    }
                    echo
                  "</select>
                  <button type='submit' class='btn btn-dark' name='cart-btn'>Add to cart</button>
                </form>

              </div>
            </div>
          </div>";
          $i = 1;
        }
        if($i == 0)echo "<p class='text-dark'>Sorry, there are no items available!</p>";
        ?>
    </div>
  </body>
</html>
