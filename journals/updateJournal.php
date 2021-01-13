<?php
    require_once('../Config/db.php');
    require_once('../Config/functions.php');

    // Define variables and initialize with empty values
    $title = $description = $author = $imagepath = $relasedate = "";
    $title_err = $author_err =  $relasedate_err = "";
    $img_err = [];

    // Processing form data when form is submitted
    if(isset($_POST["id"]) && !empty($_POST["id"])){
      // Get hidden input value
      $id = $_POST["id"];
      // Validate title
      $input_title = trim($_POST["title"]);
      if (empty($input_title))
      {
          $title_err = "Please enter a title.";
      }
      else
      {
          $title = $input_title;
      }
      //Discription
      $description = trim($_POST["description"]);
      // Validate image
      if (isset($_FILES['image']))
      {
          $img_err = [];
          $file_name = $_FILES['image']['name'];
          $file_size = $_FILES['image']['size'];
          $file_tmp = $_FILES['image']['tmp_name'];
          $file_type = $_FILES['image']['type'];
          $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));

          $extensions = ["jpeg", "jpg", "png"];

          if (in_array($file_ext, $extensions) === false))
          {
              $img_err[] = "extension not allowed, please choose a JPEG or PNG file.";
          }

          //Image size should not be more than 2 MB
          if ($file_size >= 2097152))
          {
              $img_err[] = 'File size too big 2 MB maximum!';
          }
        }

      //Validate Authros
      if (isset($_POST['authors']))
      {
          $authors = $_POST['authors'];
          $author = '';
          for ($i = 0;$i <= count($authors);$i++)
          {
              if (is_numeric($authors[$i]))
              {
                  $author .= $authors[$i] . ",";
              }
          }
      }
      else
      {
          $author_err = "Please choose at least one author";
      }
      //Validate data release
      if(isset($_POST['relase_date'])) {
      $relasedate = $_POST['relase_date'];
      //Firstly, we need to "break" the date up by using the explode function.
      $dateExploded = explode("-", $relasedate);
      //Our $dateExploded array should contain three elements.
      if(count($dateExploded) != 3){
          $relasedate_err = 'Invalid date format!';
      }
      //For the sake of clarity, lets assign our array elements to
      //named variables (day, month, year).
      $day = $dateExploded[2];
      $month = $dateExploded[1];
      $year = $dateExploded[0];
      //Finally, use PHP's checkdate function to make sure
      //that it is a valid date and that it actually occured.
      //Check if relase date is integer
      if(is_int($day) && is_int($month) && is_int($year)) {
        if(!checkdate($month, $day, $year)){
            $relasedate_err .= $relasedate . ' is not a valid date!';
        }
      }

    }
    // Check input errors before inserting in database
    if (empty($title_err) && empty($img_err) && empty($author_err) && empty($relasedate_err))
    {

          // Prepare an insert state  ment
        $sql = "UPDATE  journals SET title =?, description = ?, author = ? ,image = ?,release_date=? WHERE id=?";
        if ($stmt = mysqli_prepare($connection, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssi", $param_title, $param_description, $param_author, $param_image,$param_release_date,$param_id);
            //Make a unique folder for each image
            $foldername = strtok($file_name, ".");
            //Path to be uploaded in image
            $imagepath = ($foldername .'/'. $file_name);
            // Set parameters
            $param_title = $title;
            $param_description = $description;
            $param_author = $author;
            $param_image = $imagepath;
            $param_release_date = $relasedate;
            $param_id = $id;
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt))
            {

              //Uploading the image
              $dirname = mkdir("../images/$foldername");
              move_uploaded_file($file_tmp, "../images/$foldername/" . $file_name);

              $_POST = [];
              // Records created successfully. No errors
              $errors = false;
              //Refresh the page with
              header("location: ?id=$id&error=$error");
            }
            else
            {
                echo "Something went wrong. Please try again later. " . mysqli_error($connection);
            }
            // Close statement
            mysqli_stmt_close($stmt);
        } else {
         echo("Error description: " . mysqli_error($connection));
        }


    } else {
      //Clear the $_POST!
      $_POST = [];
      //Prepare to show the errors list;
      $errors = true;
      header("location: ?id=$id&error=$error");
    }

} else {
  // Check existence of id parameter before processing further
  if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
      // Get URL parameter
      $id =  trim($_GET["id"]);
      // Prepare a select statement
      $sql = "SELECT * FROM journals WHERE id = ?";
      if($stmt = mysqli_prepare($connection, $sql)){
          // Bind variables to the prepared statement as parameters
          mysqli_stmt_bind_param($stmt, "i", $param_id);

          // Set parameters
          $param_id = $id;

          // Attempt to execute the prepared statement
          if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
              if(mysqli_num_rows($result) == 1){
                  /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                  $title = $row['title'];
                   $description = $row['description'];
                   $author = $row['author'];
                  $imagepath = $row['image'];
                  $relasedate = $row['release_date'];
                  // Close statement
                  mysqli_stmt_close($stmt);
              } else{
                  // // URL doesn't contain valid id. Redirect to error page
                  // header("location: error.php");
                  // exit();
                    echo "Opp!22" . mysqli_error($connection);
              }

          } else{
              echo "Oops! Something went wrong. Please try again later.";
          }


      }



  }  else {
      // URL doesn't contain id parameter. Redirect to error page
      echo "Opp!" . mysqli_error($connection);
      // header("location: error.php");
      // exit();
  }
}
include '../header.php';
?>
<div class="container">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                  <?php
                  if(isset($_GET['error'])) {
                   if (filter_var($_GET['error'], FILTER_VALIDATE_BOOLEAN) === true) { ?>
                    <div class="alert alert-danger" id="falier" role="alert">
                      <h4 class="alert-heading">Oops!</h4>
                      <p>Please check the following and try again</p>
                      <hr>
                      <ul>
                          <?php echo $auth_err?  '<li>' . $author_err . '</li>' : '';?>
                          <?php echo $title_err? '<li>' . $title_err . '</li>' : '';?>
                          <?php for($i=0;$i < count($img_err); $i++) {
                                echo $img_err ? '<li>' . $img_err[$i] . '</li>' : '';
                          };?>
                            <?php echo $relasedate_err?  '<li>' . $relasedate_err . '</li>' : '';?>
                      </ul>
                    </div>

              <?php } elseif(filter_var($_GET['error'], FILTER_VALIDATE_BOOLEAN) === false) { ?>
            <div class="alert alert-success"  id="success" role="alert">
                <h4 class="alert-heading">Well done!</h4>
                  <p>Aww yeah, you successfully Updated the the Journal. "<?php echo $title;?>"</p>
                    <hr>
                    <p class="mb-0">Whenever you need to, Come back and update it again and again and again!!</p>
                  </div>
           <?php  } }?>
                    <div class="page-header">
                        <h2>Update Journal</h2>
                    </div>
                    <p>Please edit the input values and submit to update the journal.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">


                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo $title; ?>" required>
                        </div>


                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control"><?php echo $description; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>image</label>
                            <input type="file" name="image"  value="<?php echo $imagepath; ?>"><?php echo $imagepath; ?>
                        </div>

                        <div class="form-group <?php echo (!empty($salary_err)) ? '' : ''; ?>">

                              <label style="display: block">Authors</label>
                            <!--
                            SelectPicker from Bootstrap to select more than one value
                            data-droup-auto = "false" - prevent list from opening UP
                          -->
                            <select class="selectpicker form-group" multiple name="authors[]" data-dropup-auto="false" required>
                              <?php
                                                //list of the authors
                                                $sql_authors = "SELECT * FROM `authors`";
                                                if ($result = mysqli_query($connection, $sql_authors))
                                                {
                                                  if (mysqli_num_rows($result) > 0)
                                                  {
                                                      while ($row_authors = mysqli_fetch_array($result))
                                                      {

                                                          $author = '';
                                                          $author .= $row_authors['first_name'];
                                                          $author .= ' ';
                                                          $author .= $row_authors['middle_name'];
                                                          $author .= ' ';
                                                          $author .= $row_authors['last_name'];
                                                          echo '<option value=' . $row_authors['id'] . '>' . $author . '</option>';
                                                      }
                                                  }
                                                }
                                                else
                                                {
                                                  echo "ERROR: Could not able to execute $sql_authors. " . mysqli_error($connection);
                                                }

                                                  ?>
                            </select>
                        </div>


                        <div class="form-group">
                            <label>Release Date</label>
                            <input type="date" name="relase_date" class="form-control" value="<?php echo $relasedate; ?>">
                        </div>

                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="submit" name="submit">
                        <a href="viewJournals.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php include '../footer.php';
// Close connection
mysqli_close($connection);?>
