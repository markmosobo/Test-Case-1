<?php
// Include config file
require_once "config.php";

$link = mysqli_connect("localhost", "root", "", "demo");
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Get all the cities from cities table
$sql = "SELECT * FROM `cities`";
$all_cities = mysqli_query($link,$sql);

// Define variables and initialize with empty values
$firstname = $lastname = $email = $street = $zipcode = $city = "";
$firstname_err = $lastname_err = $email_err = $street_err = $zipcode_err = $city_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    $input_firstname = trim($_POST["firstname"]);
    if(empty($input_firstname)){
        $firstname_err = "Please enter a name.";
    } elseif(!filter_var($input_firstname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $firstname_err = "Please enter a valid name.";
    } else{
        $firstname = $input_firstname;
    }

     // Validate lastname
    $input_lastname = trim($_POST["lastname"]);
    if(empty($input_lastname)){
        $lastname_err = "Please enter a name.";
    } elseif(!filter_var($input_lastname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $lastname_err = "Please enter a valid name.";
    } else{
        $lastname = $input_lastname;
    }
    
    // Validate email
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Please enter an email.";     
    } else{
        $email = $input_email;
    }

    // Validate street
    $input_street = trim($_POST["street"]);
    if(empty($input_street)){
        $street_err = "Please enter a street.";     
    } else{
        $street = $input_street;
    }    
    
    // Validate ZIP code
    $input_zipcode = trim($_POST["zipcode"]);
    if(empty($input_street)){
        $zipcode_err = "Please enter zip code.";     
    } elseif(!ctype_digit($input_zipcode)){
        $zipcode_err = "Please enter a positive integer value.";
    } else{
        $zipcode = $input_zipcode;
    }

    // Validate city
    $input_city = trim($_POST["city"]);
    if(empty($input_city)){
        $city_err = "Please enter a city.";     
    } else{
        $city = $input_city;
    } 
    
    // Check input errors before inserting in database
    if(empty($firstname_err) && empty($lastname_err) && empty($email_err) && empty($street_err)
        && empty($zipcode_err) && empty($city_err)){
        // Prepare an update statement
        $sql = "UPDATE addresses SET firstname='$firstname', lastname='$lastname',
         street='$street', zipcode='$zipcode', city = '$city' WHERE id='$id' ";
         
         if(mysqli_query($link, $sql)){
            echo "Records were updated successfully.";
            header("location: index.php");
            exit();
        } else {
            echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
        }
         
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM addresses WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $firstname = $row["firstname"];
                    $lastname = $row["lastname"];
                    $email = $row["email"];
                    $street = $row["street"];
                    $zipcode = $row["zipcode"];
                    $city = $row["city"];
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
        mysqli_close($link);
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
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the address record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                                <label>First Name</label>
                                <input type="text" name="firstname" class="form-control <?php echo (!empty($firstname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $firstname; ?>">
                                <span class="invalid-feedback"><?php echo $firstname_err;?></span>
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" name="lastname" class="form-control <?php echo (!empty($lastname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lastname; ?>">
                                <span class="invalid-feedback"><?php echo $lastname_err;?></span>
                            </div>                        
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                                <span class="invalid-feedback"><?php echo $email_err;?></span>
                            </div>
                            <div class="form-group">
                                <label>Street</label>
                                <textarea name="street" class="form-control <?php echo (!empty($street_err)) ? 'is-invalid' : ''; ?>"><?php echo $street; ?></textarea>
                                <span class="invalid-feedback"><?php echo $street_err;?></span>
                            </div>
                            <div class="form-group">
                                <label>ZIP Code</label>
                                <input type="text" name="zipcode" class="form-control <?php echo (!empty($zipcode_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $zipcode; ?>">
                                <span class="invalid-feedback"><?php echo $zipcode_err;?></span>
                            </div>
                            <div class="form-group">
                                <label>City</label>
                                <select name="city" class="form-control" value="<?php echo $city; ?> ">
                                        <option value="">Select City</option>
                                    <?php 
                                        // use a while loop to fetch data 
                                        // from the $all_categories variable 
                                        // and individually display as an option
                                        while ($city = mysqli_fetch_array(
                                                $all_cities,MYSQLI_ASSOC)):; 
                                    ?>
                                        <option value="<?php echo $city["name"];
                                            // The value we usually set is the primary key
                                        ?>">
                                            <?php echo $city["name"];
                                                // To show the category name to the user
                                            ?>
                                        </option>
                                    <?php 
                                        endwhile; 
                                        // While loop must be terminated
                                    ?>
                                </select>
                                <?php echo (!empty($city_err)) ? 'is-invalid' : ''; ?>
                                <span class="invalid-feedback"><?php echo $city_err;?></span>
                            </div>  
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>