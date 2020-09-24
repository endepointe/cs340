<!DOCTYPE html>

<html>
	<head>
		<title>Student Viewer</title>
		<link rel="stylesheet" href="index.css">
	</head>
<body>

<?php

/* Change for your username, database name and password for phpMyAdmin*/
	define('DB_SERVER', 'classmysql.engr.oregonstate.edu');
	define('DB_USERNAME', 'cs340_YOUROnid');
	define('DB_PASSWORD', 'YOURpassword');
	define('DB_NAME', 'cs340_YOUROnid');
 
/* Attempt to connect to MySQL database */
	$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
	if($link === false){
		die("ERROR: Could not connect. " . mysqli_connect_error());
}
	
	$student_number = $_GET['sid'];
	$query = "SELECT STUDENT.Name, COURSE.Course_name, GRADE_REPORT.Grade
FROM GRADE_REPORT, STUDENT, COURSE, SECTION WHERE GRADE_REPORT.Student_number=STUDENT.Student_number AND
 GRADE_REPORT.Section_identifier=SECTION.Section_id AND
               SECTION.Course_number=COURSE.Course_number AND
               STUDENT.Student_number=$student_number";


	$result = mysqli_query($link, $query);
	if (!$result) {
		die("Query to show fields from table failed");
	}
	
	echo "<h1>Transcript </h1>";
	echo "<h2>Student Number: {$student_number} </h2>";
	
	// Fetch the first row.  If there is a student then dispaly course & grade
	if ($row = mysqli_fetch_row($result)) {
		echo "<h2>Student Name: $row[0] </h2>";
		// printing table headers
		echo "<table id='t01'><tr>";	
		echo "<td><b>Course</b></td>";
		echo "<td><b>Grade</b></td>";
		echo "</tr>";
		echo "<tr>";	
		// $row is array... foreach( .. ) puts every element
		// of $row to $cell variable			
		echo "<td>$row[1]</td>";
		echo "<td>$row[2]</td>";		
		echo "</tr>\n";		
		
	} else { 
		echo "No student with that number";
	}
	
	while($row = mysqli_fetch_row($result)) {	
		echo "<tr>";	
		// $row is array... foreach( .. ) puts every element
		// of $row to $cell variable			
		echo "<td>$row[1]</td>";
		echo "<td>$row[2]</td>";		
		echo "</tr>\n";
	}
	
	mysqli_free_result($result);
	mysqli_close($link);
	?>
</body>

</html>

	
