<?php
  require_once('../Config/db.php');

  $first_name = $middle_name = $last_name = '';
  $first_name_err = $last_name_err = '';

  // Processing form data when form is submitted
  if (isset($_POST['submit']))
  {
    //Validate First Name (Name)
    $first_name = trim($_POST['first_name']);
    if(empty($first_name)){
      $first_name_err = "Please enter a First name.";
    }

    //Validate Middle name
    $middle_name = trim($_POST['middle_name']);

    //Validate Last Name (family)
    $last_name = trim($_POST['last_name']);
    if(isset($_POST['last_name'])){
    //Not less than 3 Charechters
    if(strlen($last_name) < 3){
      $last_name_err = "Last name can't be less than 3 Charechters";
      }
    } else {
        $last_name_err = "Please enter a Last Name (family).";
      }


    // Check input errors before inserting in database
    if (empty($first_name_err) && empty($last_name_err))
    {
        $sql = "UPDATE  journals SET title =?, description = ?, author = ? ,image = ?,release_date=? WHERE id=?";
      $sql = "UPDATE  authors SET first_name = ?,middle_name = ?,last_name = ? WHERE id=?";

      if($stmt = mysqli_prepare($connection,$sql))
      {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sssi", $param_first_name, $param_middle_name, $param_last_name);

        // Set parameters
        $param_first_name = $first_name;
        $param_middle_name = $middle_name;
        $param_last_name = $last_name;
        $param_id = $id;
        if(mysqli_stmt_execute($stmt))
        {
          // Records created successfully. No errors
          $_POST = [];
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
      }
      else
      {
       echo("Error description: " . mysqli_error($connection));
      }

    }  else {
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
        $sql = "SELECT * FROM authors WHERE id = ?";
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
                    $first_name = $row['first_name'];
                     $middle_name = $row['middle_name'];
                     $last_name = $row['last_name'];
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
  include '../header.php'; ?>
  <div class="container">
  <div class="wrConfiger">
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
                          <?php echo $first_name_err?  '<li>' . $first_name_err . '</li>' : '';?>
                          <?php echo $last_name_err? '<li>' . $last_name_err . '</li>' : '';?>
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
                          <h2>Create Author</h2>
                      </div>
                      <p>Please fill this form and submit to add Author record to the database.</p>
                      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                          <div class="form-group">
                              <label>First Name</label>
                              <input type="text" name="first_name" class="form-control" value="<?php echo $first_name; ?>" required>
                          </div>
                          <div class="form-group">
                              <label>Middle Name</label>
                              <input name="middle_name" class="form-control" value="<?php echo $middle_name; ?>">
                          </div>
                          <div class="form-group">
                              <label>Last Name</label>
                              <input type="text" name="last_name" class="form-control"  value="<?php echo $last_name; ?>"required>
                          </div>
                          <input type="submit" class="btn btn-primary" value="submit" name="submit">
                          <a href="viewAuthors.php" class="btn btn-default">Cancel</a>
                      </form>
                  </div>
              </div>
          </div>
      </div>
    </div>

  <?php
  // Close connection
  mysqli_close($connection);
  include '../footer.php';
  ?>
