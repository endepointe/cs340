<?php

  session_start();

  if (issset($_SESSION["Ssn"])) {
    $Essn = $_SESSION["Ssn"];
  } else {
    echo "<p>No employeee ssn</p>";
    $Essn = "";
  }

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$Dname = $Sex = $Bdate = $Relationship = "";
$Dname_err = $Sex_err = $Relationship_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  // Validate name
  $Dname = trim($_POST["Dname"]);
  if(empty($Dname)){
    $Dname_err = "Please enter dependent name.";
  } elseif(!filter_var($Dname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
    $Dname_err = "Please enter a valid Name.";
  } 
    
	// Validate Sex
    $Sex = trim($_POST["Sex"]);
    if(empty($Sex)){
        $Sex_err = "Please enter Sex.";     
    }

	// Validate Birthdate
    $Bdate = $_POST["Bdate"];

	// Validate Relationship 
    $Relationship = trim($_POST["Relationship"]);
    if(empty($Relationship)){
        $Relationship_err = "Please enter a relationship.";     		
	  } elseif(!filter_var($Relationship, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
    $Relationship_err = "Please enter a valid relationship.";
  } 

    // Check input errors before inserting in database
    if (empty($Dname_err) && empty($Sex_err) && empty($Relationship_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO DEPENDENT (Essn, Dependent_name, Sex, Bdate, Relationship) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "issss", $param_Essn, $param_Dname, $param_Sex, $param_Bdate, $param_Relationship);
            
            // Set parameters
			      $param_Essn = $Essn;
            $param_Dname = $Dname;
			      $param_Sex = $Sex;
            $param_Bdate = $Bdate;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
				        header("location: viewDependents.php?Ssn='$Essn'");
					      exit();
            } else{
                echo "<center><h4>Error while creating a dependent.</h4></center>";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Dependent</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Create Dependent</h2>
                        <p><?php echo $Essn; ?></p>
                        <p><?php echo $_SESSION["Ssn"]; ?></p>
                    </div>
                    <p>Please fill this form and submit to add a Dependent record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						          <div class="form-group <?php echo (!empty($Dname_err)) ? 'has-error' : ''; ?>">
                        <label>Dependent Name</label>
                        <input type="text" name="Dname" class="form-control" value="<?php echo $Dname; ?>">
                        <span class="help-block"><?php echo $Dname_err;?></span>
                      </div>
						          <div class="form-group <?php echo (!empty($Sex_err)) ? 'has-error' : ''; ?>">
                        <label>Sex</label>
                        <input type="text" name="Sex" class="form-control" value="<?php echo $Sex; ?>">
                        <span class="help-block"><?php echo $Sex_err;?></span>
                      </div>
						          <div class="form-group <?php echo (!empty($Bdate_err)) ? 'has-error' : ''; ?>">
                        <label>Birth date</label>
                        <input type="date" name="Bdate" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                        <span class="help-block"><?php echo $Bdate_err;?></span>
                      </div>
                      <div class="form-group <?php echo (!empty($Relationship_err)) ? 'has-error' : ''; ?>">
                        <label>Relationship</label>
                        <input type="text" name="Relationship" class="form-control" value="<?php echo $Relationship; ?>">
                        <span class="help-block"><?php echo $Relationship_err;?></span>
                      </div>
                      <input type="submit" class="btn btn-primary" value="Submit">
                      <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>