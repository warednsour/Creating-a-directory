<?php
require_once ('../db.php');
include '../header.php';

// Processing form data when form is submitted
if (isset($_POST['submit']))
{

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
    $discription = trim($_POST["description"]);

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

        if (in_array($file_ext, $extensions) === false)
        {
            $img_err[] = "extension not allowed, please choose a JPEG or PNG file.";
        }

        //Image size should not be more than 2 MB
        if ($file_size > 2097152)
        {
            $img_err[] = 'File size must be excately 2 MB';
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
                $author .= $authors[$i] . " ";
            }
        }
    }
    else
    {
        $author_err = "Please choose at least one author";
    }

    //Validate data release
    $relasedate = $_POST['relase_date'];


    // Check input errors before inserting in database
    if (empty($title_err) && empty($img_err) && empty($author_err))
    {

        //Uploading the image
        //Make a unique folder for each image
        $foldername = strtok($file_name, ".");
        $dirname = mkdir("../images/$foldername");
        move_uploaded_file($file_tmp, "../images/$foldername/" . $file_name);
        $imagepath = ($foldername .'/'. $file_name);
        // Prepare an insert statement
        $sql = "INSERT INTO journals (title, description, author,image,release_date) VALUES (?,?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($connection, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "isssss", $param_title, $param_description, $param_author, $param_image,$param_release_date);

            // Set parameters
            $param_title = $title;
            $param_description = $description;
            $param_author = $author;
            $param_image = $image;
            $param_release_date = $relasedate;
            // Attempt to execute the prepared statement
          echo  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            if (mysqli_stmt_execute($stmt))
            {
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            }
            else
            {
                echo "Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

}
?>
<div class="container">
<div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                  <?php if ($errors)
{ ?>
                      <div class="alert alert-success" role="alert">
                          <h4 class="alert-heading">Well done!</h4>
                            <p>Aww yeah, you successfully read this important alert message. This example text is going to run a bit longer so that you can see how spacing within an alert works with this kind of content.</p>
                              <hr>
                              <p class="mb-0">Whenever you need to, be sure to use margin utilities to keep things nice and tidy.</p>
                            </div>
                  <?php
} ?>
                    <div class="page-header">
                        <h2>Create Journal</h2>
                    </div>
                    <p>Please fill this form and submit to add Journal record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">


                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo $title; ?>" required>
                            <span class="help-block"><?php echo $title_err; ?></span>
                        </div>


                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control"><?php echo $description; ?></textarea>
                        </div>

                        <div class="form-group <?php echo (!empty($salary_err)) ? '' : ''; ?>">
                            <label>image</label>
                            <input type="file" name="image"  value="<?php echo $image; ?>">
                            <span class="help-block"><?php echo $image_err; ?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($salary_err)) ? '' : ''; ?>">

                              <label style="display: block">Authors</label>
                            <!--
                            SelectPicker from Bootstrap to select more than one value
                            data-droup-auto = "false" - prevent list from opening UP
                          -->
                            <select class="selectpicker form-group" multiple name="authors[]" data-dropup-auto="false">
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
// Close connection
mysqli_close($connection);
?>
                            </select>
                            <span class="help-block"><?php echo $author_err; ?></span>
                        </div>


                        <div class="form-group <?php echo (!empty($salary_err)) ? 'alert alert-danger' : ''; ?>">
                            <label>Release Date</label>
                            <input type="date" name="relase_date" class="form-control" value="<?php echo $salary; ?>">
                            <span class="help-block"><?php echo $salary_err; ?></span>
                        </div>


                        <input type="submit" class="btn btn-primary" value="submit" name="submit">
                        <a href="viewJournals.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
  </div>

<?php include '../footer.php' ?>
