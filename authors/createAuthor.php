<?php
require_once ('../Config/db.php');

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
    $sql = "INSERT INTO authors (first_name,middle_name,last_name) VALUES (?,?,?)";

    if($stmt = mysqli_prepare($connection,$sql))
    {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "sss", $param_first_name, $param_middle_name, $param_last_name);

      // Set parameters
      $param_first_name = $first_name;
      $param_middle_name = $middle_name;
      $param_last_name = $last_name;

      if(mysqli_stmt_execute($stmt))
      {
        // Records created successfully. No errors
        $_POST = [];
        $errors = false;
        header("Refresh:3");
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
  }
}
include '../header.php'; ?>
<div class="container">
<div class="wrConfiger">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                  <?php if ($errors === true) { ?>
                    <div class="alert alert-danger" id="falier" role="alert">
                      <h4 class="alert-heading">Oops!</h4>
                      <p>Please check the following and try again</p>
                      <hr>
                      <ul>
                          <?php echo $first_name_err?  '<li>' . $first_name_err . '</li>' : '';?>
                          <?php echo $last_name_err? '<li>' . $last_name_err . '</li>' : '';?>
                      </ul>
                    </div>
              <?php } elseif($errors === false) { ?>
            <div class="alert alert-success"  id="success" role="alert">
                <h4 class="alert-heading">Well done!</h4>
                  <p>Aww yeah, you successfully Created a Author.</p>
                    <hr>
                    <p class="mb-0">Whenever you need to, Come back and create another one!.</p>
                  </div>
           <?php  } ?>
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
