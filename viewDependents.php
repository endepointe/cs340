<?php
  session_start();
  if (issset($_SESSION["Ssn"]) ) {
    $Essn = $_SESSION["Ssn"];
  } else {
    echo "<B>No employeee ssn</B>";
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dependents</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <style type="text/css">
        .wrapper{
            width: 650px;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Dependent Details</h2>

                        <a href="createDependent.php" class="btn btn-success pull-right">Add New Dependent</a>
                    </div>

                    <?php

                    // Include config file
                    require_once "config.php";
                    // Check existence of id parameter before processing further
                    if(isset($_GET["Ssn"]) && !empty(trim($_GET["Ssn"]))){
                      $_SESSION["Ssn"] = $_GET["Ssn"];                      
                      $Ssn = $_GET["Ssn"];
                    }

                    echo "SSN = '$Ssn'";

                    // Attempt select query execution
                    $sql = "SELECT Dependent_name, Sex, Bdate, Relationship  FROM DEPENDENT WHERE Essn = ?";

                    $stmt = mysqli_prepare($link, $sql); 
                    mysqli_stmt_bind_param($stmt, "s", $param_Ssn);
                    $param_Ssn = $Ssn;

                    // Attempt to execute to prepared statement
                    if(mysqli_stmt_execute($stmt)){
                        $result = mysqli_stmt_get_result($stmt);
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th width=10%>Dependent_name</th>";
                                        echo "<th width=10%>Sex</th>";
                                        echo "<th width=10%>Bdate</th>";
                                        echo "<th width=10%>Relationship</th>";
                                        echo "<th width=10%>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['Dependent_name'] . "</td>";
                                        echo "<td>" . $row['Sex'] . "</td>";
                                        echo "<td>" . $row['Bdate'] . "</td>";
                                        echo "<td>" . $row['Relationship'] . "</td>";
                                        echo "<td>";
                                            echo "<a href='update.php?id=". $row['id'] ."' title='Update Record' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                            echo "<a href='delete.php?id=". $row['id'] ."' title='Delete Record' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";

                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }
 
                    // Close connection
                    mysqli_close($link);
                    ?>
<p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>