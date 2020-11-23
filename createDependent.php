<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$Essn = $Dependent_name = $Sex = $Bdate = $Relationship;
$Essn_err = $Dependent_name_err = $Sex_err = $Bdate_err = $Relationship_err;
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

  // Validate Essn
  $Essn = trim($_POST["Ssn"]);
  if(empty($Essn)){
    $Essn_err = "Please enter SSN.";     
  } elseif(!ctype_digit($Essn)){
    $Essn_err = "Please enter a positive integer value of SSN.";
  } 

  // Validate name
  $Dependent_name = trim($_POST["Dependent_name"]);
  if(empty($Dependent_name)){
    $Dependent_name = "Please enter a Fname.";
  } elseif(!filter_var($Dependent_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
    $Dependent_name_err = "Please enter a valid Name.";
  } 
    
	// Validate Sex
    $Sex = trim($_POST["Sex"]);
    if(empty($Sex)){
        $Sex_err = "Please enter Sex.";     
    }

	// Validate Birthdate
    $Bdate = trim($_POST["Bdate"]);

    if(empty($Bdate)){
      $Bdate_err = "Please enter birthdate.";     
    }	

	// Validate Relationship 
    $Relationship = trim($_POST["Relationship"]);
    if(empty($Relationship)){
        $Relationship_err = "Please enter a department number.";     		
	}
    // Check input errors before inserting in database
    if(empty($Essn_err) && empty($Dependent_name_err) && empty($Sex_err) 
				&& empty($Bdate_err)&& empty($Relationship_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO DEPENDENT (Essn, Dependent_name, Sex, Bdate, Relationship) 
		        VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "issds", $param_Essn, $param_Dependent_name, $param_Sex, $param_Bdate, $param_Relationship);
            
            // Set parameters
			$param_Essn = $Essn;
			$param_Dependent_name = $Dependent_name;
			$param_Sex = $Sex;
			$param_Bdate = $Bdate;
      $param_Relationship = $Relationship;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
				    header("location: index.php");
					exit();
            } else{
                echo "<center><h4>Error while creating new dependent.</h4></center>";
        $Essn_err = "Enter a unique Ssn.";
        $Dependent_name_err = "Enter a dependent name.";
        $Sex_err = "Enter a sex.";
        $Bdate_err = "Enter a birthdate.";
        $Relationship_err = "Enter a relationship.";
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
                    </div>
                    <p>Please fill this form and submit to add a Dependent record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						<div class="form-group <?php echo (!empty($Essn_err)) ? 'has-error' : ''; ?>">
                            <label>ESSN</label>
                            <input type="text" name="Essn" class="form-control" value="<?php echo $Essn; ?>">
                            <span class="help-block"><?php echo $Essn_err;?></span>
                        </div>
                 
						<div class="form-group <?php echo (!empty($Dependent_name_err)) ? 'has-error' : ''; ?>">
                            <label>Dependent Name</label>
                            <input type="text" name="Dependent_name" class="form-control" value="<?php echo $Dependent_name; ?>">
                            <span class="help-block"><?php echo $Dependet_name_err;?></span>
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