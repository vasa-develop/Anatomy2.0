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
 * @file editcourse.php
 * Webpage for editing a single course.
 */
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv='X-UA-Compatible' content='IE=edge' charset='utf8'/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Your Course</title>

    <!-- X3Dom includes -->
    <script type='text/javascript' src='../js/x3dom.js'> </script>

    <script type='text/javascript' src='../js/x3d-extensions.js'> </script>
    <script type='text/javascript' src='../js/viewer.js'> </script>
    <link type='text/css' rel='stylesheet' href='http://www.x3dom.org/download/x3dom.css'> </link>

    <link rel='stylesheet' type='text/css' href='../css/model_viewer.css'></link>

    <!-- Additional styles -->
    <link rel='stylesheet' type='text/css' href='../css/bootstrap.min.css'>
    <link rel='stylesheet' type='text/css' href='../css/style.css'>
    <link rel="stylesheet" href="../css/editcourse.css">
  
    <!-- General functionality (used in menuToolbar.js) -->
    <script type="text/javascript" src="../js/tools.js"></script>
    <!-- The library for the copy to clipboard feature in the toolbar -->
    <script type="text/javascript" src="../js/ZeroClipboard.js"></script>
    
    <script src="../js/ajax.js"></script>
    <script src="../js/editcourse.js"></script>
    <script src="../js/search.js"></script>
    <script src="../js/tools.js"></script>

  </head>

  <body>
    <?php 
      //Decide if this site is inside a separate widget
      if(isset($_GET["widget"]) && $_GET["widget"] == "true")
      {
          print("<script type='text/javascript' src='../js/model-viewer-widget.js'> </script>");
          print("<script type='text/javascript' src='../js/init-subsite.js'></script>");
      }
      include("menu.php"); 
      include "../php/db_connect.php";
      include '../php/tools.php';

      if (isset($_SESSION['user_id'])) {
        $query  = $db->query("SELECT * FROM courses WHERE id = $_GET[id]");
        $entry = $query->fetchObject();
        $arg = $_GET["id"];
      }

      // If the user is not logged in or he is not the creator, redirect him to the login page
      if(!isset($entry->creator) || $entry->creator != $_SESSION['user_id']) { 
        header("Location: login.php");
        exit();
      }
    ?>
    
    <header id='head' class='secondary'>
    <div class='container'>
      <div class='row'>
          <h1>Edit Your Course</h1>
      </div>
    </div>
    </header>

    <div id='courses'>
      <section class='container'>
        <br><br>
      <div class='container'>
        <div class='row'>
          <div class='col-md-6'>
            <div class='featured-box'>
              Enter course name, course room URL, contact, description and dates below. If you would like help on how to create your course room, press the "?" button.<br>
              To show models in your course room, add models on the right side. You can delete models by pressing the red "x". Those models will not be shown in your course room.<br>
              Press "Save when you are done.
            </div>
            
            <form role="form" action="../php/edit_script_course.php<?php if(isset($_GET['widget']) && $_GET['widget'] == true) {echo '?widget=true';} ?>" method="post" enctype="multipart/form-data" id="UploadForm">              
              <div class="form-group">
                <input type="hidden" name="targetId" value="<?php echo $arg; ?>">
                <label for="targetName">Course name:</label>
                <input type="text" class="form-control" rows="1" name="name" id="targetName" value="<?php echo htmlentities($entry->name); ?>" required>
              </div>
              <div class="form-group">
                <label for="targetText">Course room:</label>
                <input type="text" class="form-control" rows="1" name="roleLink" id="targetRole" value="<?php echo $entry->role_url; ?>">
                <button class="col-sm-1 btn btn-default btn-inline" tpye="button">?</button>
              </div>
              <div class="form-group">
                <label for="targetContact">Contact:</label>
                <textarea class="form-control" rows="3" name="contact" id="targetContact"><?php echo htmlentities($entry->contact) ?></textarea>
              </div>
              <div class="form-group">
                <label for="targetText">Description:</label>
                <textarea class="form-control" rows="3" name="text" id="targetText"><?php echo htmlentities($entry->description) ?></textarea>
              </div>
              <div class="form-group">
                <label for="targetDates">Dates:</label>
                <textarea class="form-control" rows="3" name="dates" id="targetDates"><?php echo htmlentities($entry->dates) ?></textarea>
              </div>
              <button type="submit" class="btn btn-success col-xs-6" id="SubmitButton" value="Upload">Save</button>
            </form>
            <br>
          </div> 

          <div class='col-md-6'>
            <div><h3>Models</h3></div>
            <!-- Buttons to create add and upload models -->
            <button class='btn btn-success col-xs-6' type='button' id="openbox" onclick="startBlackout()">Add</button>
            
            <div id="model_table" class="col-xs-12 model_div">
            <?php 
              $query = $db->query("SELECT * 
                                   FROM course_models
                                   INNER JOIN models ON course_models.model_id = models.id
                                   WHERE course_models.course_id = $arg");
              $result = $query->fetchAll();

              $html = createTable($result,"modeldeletion");
              echo $html;
            ?>
            </div>
           
          </div>
        </div>
      </div>  
      </section>
    </div>
    <!-- container -->

      
    </div>

    <!-- Darken background when model select window appears -->
    <div id="blackout" onclick="endBlackout()"></div>

    <!-- Show models in a pop-up -->
    <div id="modelbox">
      <div id="closebox" onclick="endBlackout()">close</div>
      <button class='btn btn-success' type='button' id="addmodels" onclick="addModels()">Add models to course</button>
      <?php include("search.php"); ?>
      <div id="result-container">
      <!-- Models will be inserted here -->
      </div>
    </div>
  
    
    <?php include("footer.php");?>
  </body>
</html>
