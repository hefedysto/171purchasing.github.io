<?php
// Include config file
require_once "config.php";
require_once "session.php";

// Define variables and initialize with empty values
$order_id = $product_id = $qty_order = $quoted_price = "";
$order_id_err = $product_id_err = $qty_order_err = $quoted_price_err = "";

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value

    // Validate name
    $input_order_id = trim($_POST["order_id"]);
    if(empty($input_order_id)){
        $order_id_err = "Please enter an order ID.";
    } else{
        $order_id = $input_order_id;
    }

    // Validate address address
    $input_product_id = trim($_POST["product_id"]);
    if(empty($input_product_id)){
        $product_id_err = "Please enter a product ID.";
    } else{
        $product_id = $input_product_id;
    }

    // Validate salary
    $input_qty_order = trim($_POST["qty_order"]);
    if(empty($input_qty_order)){
        $qty_order_err = "Please enter a quantity.";
    } else{
        $qty_order = $input_qty_order;
    }

    // Validate salary
    $input_quoted_price = trim($_POST["quoted_price"]);
    if(empty($input_quoted_price)){
        $quoted_price_err = "Please enter a price.";
    } else{
        $quoted_price = $input_quoted_price;
    }

    $id = $_POST['id'];

    // Check input errors before inserting in database
    if(empty($order_id_err) && empty($product_id_err) && empty($qty_order_err) && empty($quoted_price_err)){
        // Prepare an update statement
        $sql = "UPDATE purchase_order_details SET order_id=?, product_id=?, qty_order=?, quoted_price=? WHERE id=?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "iiidi", $param_order_id, $param_product_id, $param_qty_order, $param_quoted_price, $param_id);

            // Set parameters
            $param_order_id = $order_id;
            $param_product_id = $product_id;
            $param_qty_order = $qty_order;
            $param_quoted_price = $quoted_price;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: purchase-order-read.php?order_id=$order_id");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM purchase_order_details WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $order_id = $row["order_id"];
                    $product_id = $row["product_id"];
                    $qty_order = $row["qty_order"];
                    $quoted_price = $row["quoted_price"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

        // Close connection
        // mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Purchase Order Detail</title>
    <link rel="icon" href="img/rob.png" type="image/ico">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <link href="fontawesome-free-5.5.0-web/css/all.css" rel="stylesheet">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>

  <!-- Navigation Bar (Top) -->
      <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top" >
        <a class="navbar-brand" href="#">
          <img src="img/rob.png" width="30" height="30" class="d-inline-block align-top" alt="">
          Purchasing Department
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

      <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
          <li class="nav-item ">
            <a class="nav-link" href="welcome.php">Home </a>
          </li>
          <li class="nav-item dropdown">
            <li class="nav-item ">
              <a class="nav-link" href="warehouse-purchase-request.php">Purchase Requests</a>
            </li>
            <li class="nav-item dropdown">
              <li class="nav-item">
                <a class="nav-link" href="product.php">Product</a>
            </li>
            <li class="nav-item dropdown active">
            <a class="nav-link dropdown-toggle" href="purchase-orders.php" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Purchase Orders
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="purchase-orders.php">List of Purchase Orders</a>
              <!-- <a class="dropdown-item" href="purchase-order-create.php">Add a Purchase Order</a> -->
            </div>
          </li>
          <li class="nav-item dropdown ">
            <a class="nav-link dropdown-toggle" href="suppliers.php" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Suppliers <span class="sr-only">(current)</span>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="canvassing.php">Canvassing</a>
              <a class="dropdown-item" href="suppliers.php">List of Suppliers</a>
              <a class="dropdown-item" href="supplier-create.php">Add a Supplier</a>
            </div>
          </li>
          <li class="nav-item">
                <a class="nav-link" href="about.php">About</a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
          <li class="nav-item ">
            <a class="nav-link" href="logout.php"><?php echo htmlspecialchars($_SESSION["username"]); ?> <i class="btn btn-sm fas fa-sign-out-alt" title="Sign out"></i></a>
          </li>
        </ul>
      </div>
      </nav>
  <!-- /Navigation Bar (Top) -->

    <!-- Content -->
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <br><h2>Update Purchase Order Detail</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($order_id_err)) ? 'has-error' : ''; ?>">
                            <label>Order ID</label>
                            <input type="text" name="order_id" class="form-control" value="<?php echo $order_id; ?>" readonly>
                        </div>
                        <div class="form-group <?php echo (!empty($product_id_err)) ? 'has-error' : ''; ?>">
                            <label>Product</label>
                            <select type="text" name="product_id" class="custom-select" value="<?php echo $product_id; ?>" required>
                            <optgroup label="Current Product">
                                <?php $sql2 = "SELECT * FROM product WHERE product_id = $product_id"; 
                                        if($result2 = mysqli_query($link,$sql2)){
                                            if(mysqli_num_rows($result2) > 0){
                                                $row2 = mysqli_fetch_array($result2);
                                            }
                                        }
                                ?>
                                <option value="<?php echo $product_id; ?>"><?php echo $row2['description']; ?></option>
                            </optgroup>
                            <optgroup label="Products">
                                <?php 
                                    $sql3 = "SELECT * FROM product";
                                    if($result3 = mysqli_query($link, $sql3)){
                                    if(mysqli_num_rows($result3) > 0){
                                        while($row3 = mysqli_fetch_array($result3)){
                                        echo $data3 = $row3['product_id'];
                                        echo "<option value='$data3'>" . $row3['description'] . "</option>";
                                        }
                                    }
                                    }
                                ?>
                            </optgroup>
                            </select>
                        </div>
                        <div class="form-group <?php echo (!empty($qty_order_err)) ? 'has-error' : ''; ?>">
                            <label>Quantity</label>
                            <input type="number" name="qty_order" class="form-control" value="<?php echo $qty_order; ?>" required>
                        </div>
                        <div class="form-group <?php echo (!empty($quoted_price_err)) ? 'has-error' : ''; ?>">
                            <label>Quoted Price</label>
                            <input type="text" step="0.01" name="quoted_price" class="form-control" value="<?php echo $quoted_price; ?>" required>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a class="btn btn-danger" href="purchase-order-read.php?order_id=<?php echo $order_id;?>" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php mysqli_close($link); ?>
    <!-- End of Content -->

    <!-- JavaScript Frameworks -->
    <script src="jquery/jquery-3.3.1.slim.min.js"></script>
    <script src="jquery/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- JavaScript Frameworks -->    
</body>
</html>
