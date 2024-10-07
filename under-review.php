<?php

//To Handle Session Variables on This Page
session_start();

//If user Not logged in then redirect them back to homepage. 
//This is required if user tries to manually enter view-job-post.php in URL.
if (empty($_SESSION['id_company'])) {
  header("Location: ../index.php");
  exit();
}

//Including Database Connection From db.php file to avoid rewriting in all files  
require_once("../db.php");

$sql = "SELECT * FROM apply_job_post WHERE id_company='$_SESSION[id_company]' AND id_user='$_GET[id]' AND id_jobpost='$_GET[id_jobpost]'";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
  header("Location: index.php");
  exit();
}


// i am changing the default status to value 0 in order to give the output that the student is placed. 

// under review code is now placed student code

$sql = "UPDATE apply_job_post SET status='0' WHERE id_company='$_SESSION[id_company]' AND id_user='$_GET[id]' AND id_jobpost='$_GET[id_jobpost]'";
if ($conn->query($sql) === TRUE) {
  header("Location: job-applications.php");
  exit();
}
