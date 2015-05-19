<?php 
/**
 * Copyright 2015 Adam Brunnmeier, Dominik Studer, Alexandra Wörner, Frederik Zwilling, Ali Demiralp, Dev Sharma, Luca Liehner, Marco Dung, Georgios Toubekis
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @file upload_script_course.php
 * 
 * Adss new course to the course database on the server
 * adds metadata about it database.
 */

session_start();

//create database connection (needs to be done before mysql_real_escape_string)
$conn = require '../php/db_connect.php';


//Get input data from form
$name = mysql_real_escape_string($_POST['name']);
$text = mysql_real_escape_string($_POST['text']);
$role_link = $_POST['roleLink'];
$preview_img_link = $_POST['previewImgLink'] != "" ? $_POST['previewImgLink'] : "https://www.symplicity.com/assets/Icon_-_Product_Features_-_Tutor_New.jpg";

// Get id of currently logged in user
$creator = $_SESSION["user_id"];
	
// Create database-entry
$sql = "INSERT INTO courses (name, description, creator, role_url, img_url) VALUES ('$name','$text', $creator, '$role_link', '$preview_img_link')";

$conn->query($sql);

$last_id = $conn->lastInsertId();

$html = "";
if(isset($_GET['widget']) && $_GET['widget'] == 'true') {$html = "&widget=true";}

header("Location: ../views/course.php?id=$last_id$html");

?>