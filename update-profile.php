<?php

//To Handle Session Variables on This Page
session_start();

if (empty($_SESSION['id_user'])) {
	header("Location: ../index.php");
	exit();
}

//Including Database Connection From db.php file to avoid rewriting in all files
require_once("../db.php");

//if user Actually clicked update profile button
if (isset($_POST)) {

	//Escape Special Characters
	$firstname = mysqli_real_escape_string($conn, $_POST['fname']);
	$lastname = mysqli_real_escape_string($conn, $_POST['lname']);
	$address = mysqli_real_escape_string($conn, $_POST['address']);
	$city = mysqli_real_escape_string($conn, $_POST['city']);
	$state = mysqli_real_escape_string($conn, $_POST['state']);
	$contactno = mysqli_real_escape_string($conn, $_POST['contactno']);
	$qualification = mysqli_real_escape_string($conn, $_POST['qualification']);
	$stream = mysqli_real_escape_string($conn, $_POST['stream']);
	$skills = mysqli_real_escape_string($conn, $_POST['skills']);
	$aboutme = mysqli_real_escape_string($conn, $_POST['aboutme']);
	$Hsc = mysqli_real_escape_string($conn, $_POST['hsc']);
	$Ssc = mysqli_real_escape_string($conn, $_POST['ssc']);
	$UG = mysqli_real_escape_string($conn, $_POST['ug']);
	$PG = mysqli_real_escape_string($conn, $_POST['pg']);

	$uploadOk = true;

	if (isset($_FILES)) {

		$folder_dir = "../uploads/resume/";

		$base = basename($_FILES['resume']['name']);

		$resumeFileType = pathinfo($base, PATHINFO_EXTENSION);

		$file = uniqid() . "." . $resumeFileType;

		$filename = $folder_dir . $file;

		if (file_exists($_FILES['resume']['tmp_name'])) {

			if ($resumeFileType == "pdf") {

				if ($_FILES['resume']['size'] < 500000) { // File size is less than 5MB

					move_uploaded_file($_FILES["resume"]["tmp_name"], $filename);
				} else {
					$_SESSION['uploadError'] = "Wrong Size. Max Size Allowed : 5MB";
					header("Location: edit-profile.php");
					exit();
				}
			} else {
				$_SESSION['uploadError'] = "Wrong Format. Only PDF Allowed";
				header("Location: edit-profile.php");
				exit();
			}
		}
	} else {
		$uploadOk = false;
	}



	//Update User Details Query
	$sql = "UPDATE users SET firstname='$firstname', lastname='$lastname', address='$address', city='$city', state='$state', contactno='$contactno', qualification='$qualification', stream='$stream', skills='$skills', aboutme='$aboutme', Hsc='$Hsc', Ssc='$Ssc', UG='$UG', PG='$PG'";

	if ($uploadOk == true) {
		$sql .= ", resume='$file'";
	}

	$sql .= " WHERE id_user='$_SESSION[id_user]'";

	if ($conn->query($sql) === TRUE) {
		$_SESSION['name'] = $firstname . ' ' . $lastname;
		//If data Updated successfully then redirect to dashboard
		header("Location: index.php");
		exit();
	} else {
		echo "Error " . $sql . "<br>" . $conn->error;
	}
	//Close database connection. Not compulsory but good practice.
	$conn->close();
} else {
	//redirect them back to dashboard page if they didn't click update button
	header("Location: edit-profile.php");
	exit();
}
