<?php

// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include db file and functions
    require_once('../App/db.php');
    require_once('../App/functions.php');
    // Prepare a select statement
    $sql = "SELECT * FROM journals WHERE id = ?";

    if($stmt = mysqli_prepare($connection, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        $param_id = trim($_GET["id"]);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
              $title = $row['title'];
               $description = $row['description'];
               $author = getAuthors($row['author']);
                $imagepath = $row['image'];
              $relasedate = $row['release_date'];
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>View Journal</h1>
                    </div>
                    <div class="form-group">
                        <label>title</label>
                        <p class="form-control-static"><?php echo $title; ?></p>
                    </div>
                    <div class="form-group">
                        <label>description</label>
                        <p class="form-control-static"><?php echo $description; ?></p>
                    </div>
                    <div class="form-group">
                        <label>image</label>
                        <img class="form-control-static" style="width: 50%;" src ="../images/<?php echo $imagepath; ?>">
                    </div>
                    <div class="form-group">
                        <label>author</label>
                        <?php for($i= 0;$i < count($author); $i++){ ?>
                        <p class="form-control-static"><?php echo $author[$i]?></p>
                    <?php    }?>
                    </div>
                    <div class="form-group">
                        <label>release date</label>
                        <p class="form-control-static"><?php echo $relasedate; ?></p>
                    </div>
                    <p><a href="viewJournals.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
