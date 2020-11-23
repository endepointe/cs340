<?php
	session_start();	
// Include config file
	require_once "config.php";
 
// Define variables and initialize with empty values
$Dname = $Sex = $Bdate = $Relationship = "";
$Dname_err = $Sex_err = $Relationship_err = "";

// Form default values

if(isset($_GET["Dname"]) && !empty(trim($_GET["Dname"]))){
  $_SESSION["Dname"] = $_GET["Dname"];
  $OldDname = $_GET['Dname'];
  // ssn cannot be modified/deleted
  $Essn = $_SESSION['Ssn'];

    // Prepare a select statement
    $sql1 = "SELECT Dependent_name, Sex, Bdate, Relationship FROM DEPENDENT WHERE Essn = ? AND Dependent_name = ?";
  
    if($stmt1 = mysqli_prepare($link, $sql1)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt1, "is", $param_Essn, $param_Dname);      
        // Set parameters
       $param_Essn = $Essn;
       $param_Dname = $OldDname;

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt1)){
            $result1 = mysqli_stmt_get_result($stmt1);
			if(mysqli_num_rows($result1) > 0){

        $row = mysqli_fetch_array($result1);

        $Sex = $row['Sex'];
        $Bdate = $row['Bdate'];
				$Relationship = $row['Relationship'];
			}
		}
	}
}
 
// Post information about the dependent when the form is submitted
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $Essn = $_SESSION["Ssn"];
    $Dname = trim($_POST["Dname"]);

    if(empty($Dname)){
        $Dname_err = "Please enter a dependent name.";
    } elseif(!filter_var($Dname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $Dname_err = "Please enter a valid name.";
    } 

    $Relationship = trim($_POST["Relationship"]);
    if(empty($Relationship)){
        $Relationship_err = "Please enter a relationship.";
    } elseif(!filter_var($Relationship, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $Relationship_err = "Please enter a valid relationship.";
    }  

    $Sex = trim($_POST["Sex"]);
    if(empty($Sex)){
        $Sex_err = "Please enter a sex.";     
    }
	
    $Bdate = $_POST["Bdate"];
	
    // Check input errors before inserting into database
    if(empty($Dname_err) && empty($Relationship_err) && empty($Sex_err)){
        // Prepare an update statement
        $sql = "UPDATE DEPENDENT SET Dependent_name=?, Sex=?, Bdate=?, Relationship=? WHERE Essn=? AND Dependent_name = ?";
    
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssis", $param_Dname, $param_Sex,$param_Bdate, $param_Relationship, $param_Essn, $param_Dname);

            // Set parameters
			      $param_Essn = $Essn;
            $param_Dname = $Dname;
			      $param_Sex = $Sex;
            $param_Bdate = $Bdate;
            $param_Relationship = $Relationship;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: viewDependent.php?Ssn=$Essn");
                exit();
            } else{
                echo "<center><h2>Error when updating</center></h2>";
            }
        }        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else {
/*
    // Check existence of sID parameter before processing further
	// Form default values

	if(isset($_GET["Ssn"]) && !empty(trim($_GET["Ssn"]))){
		$_SESSION["Ssn"] = $_GET["Ssn"];

		// Prepare a select statement
		$sql1 = "SELECT Dependent_name, Sex, Bdate, Relationship  FROM DEPENDENT WHERE Essn = ?";
  
		if($stmt1 = mysqli_prepare($link, $sql1)){
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt1, "s", $param_Essn);      
			// Set parameters
		$param_Essn = trim($_GET["Ssn"]);

			// Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt1)){
				$result1 = mysqli_stmt_get_result($stmt1);
				if(mysqli_num_rows($result1) == 1){

					$row = mysqli_fetch_array($result1);

				$Dname = $row['Dname'];
        $Sex = $row['Sex'];
        $Bdate = $row['Bdate'];
        $Relationship = $row['Relationship'];

				} else{
					// URL doesn't contain valid id. Redirect to error page
					header("location: error.php");
					exit();
				}
                
			} else{
				echo "Error in SSN while updating";
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
  */
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Dependent</title>
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
                        <h3>Update dependent for SSN =  <?php echo $Essn; ?> </H3>
                    </div>
                    <p>Please edit the input values and submit to update.
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						          <div class="form-group <?php echo (!empty($Dname_err)) ? 'has-error' : ''; ?>">
                        <label>Dependent Name</label>
                        <input type="text" name="Dname" class="form-control" value="<?php echo $OldDname;?>">
                        <span class="help-block"><?php echo $Dname_err;?></span>
                      </div>
						          <div class="form-group <?php echo (!empty($Sex_err)) ? 'has-error' : '';?>">
                        <label>Sex</label>
                        <input type="text" name="Sex" class="form-control" value="<?php echo $Sex;?>">
                        <span class="help-block"><?php echo $Sex_err;?></span>
                      </div>
						          <div class="form-group <?php echo (!empty($Bdate_err)) ? 'has-error' : ''; ?>">
                        <label>Birth date</label>
                        <input type="date" name="Bdate" class="form-control" value="<?php echo date('Y-m-d');?>">
                        <span class="help-block"><?php echo $Bdate_err;?></span>
                      </div>
                      <div class="form-group <?php echo (!empty($Relationship_err)) ? 'has-error' : '';?>">
                        <label>Relationship</label>
                        <input type="text" name="Relationship" class="form-control" value="<?php echo $Relationship;?>">
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