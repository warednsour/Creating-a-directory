<?php

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){

    // Include db file and functions
    require_once('../Config/db.php');
    require_once('../Config/functions.php');
    // Prepare a select statement
    $sql = "SELECT * FROM authors WHERE id = ?";

    if($stmt = mysqli_prepare($connection, $sql)){

        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        $param_id = trim($_GET["id"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){

            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result)){
    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
              $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
              $first_name = $row['first_name'];
              $middle_name = $row['middle_name'];
              $last_name = $row['last_name'];
              $list_of_journals = getJournalsList($row['id']);
                // Retrieve individual field value

            } else{
                // URL doesn't contain valid id parameter. Redirect to error page

                header("location: error.php");
                exit();
            }

        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($connection);
} else {
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
 include('../header.php');
?>

    <div class="container">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>View Author</h1>
                    </div>
                    <div class="form-group">
                        <label>First name</label>
                        <p class="form-control-static"><?php echo   $first_name ?></p>
                    </div>
                    <div class="form-group">
                        <label>middle name</label>
                        <p class="form-control-static"><?php echo   $middle_name; ?></p>
                    </div>

                    <div class="form-group">
                        <label>last name</label>
                        <p class="form-control-static"><?php echo   $last_name; ?></p>
                    </div>
                    <div class="form-group">
                        <label>List of Journals</label>
                        <?php for($i = 0; $i < count($list_of_journals) ; $i++){?>
                        <p class="form-control-static"><?php echo $list_of_journals[$i] ?></p>
                      <?php } ?>
                    </div>
                    <p><a href="viewAuthors.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>
        </div>
    </div>
<?php include('../footer.php'); ?>
