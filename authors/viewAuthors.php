<?php
  require_once('../App/functions.php');


?>

<?php include('../header.php'); ?>


<div class="container">
<div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Authors Details</h2>
                        <a href="createAuthor.php" class="btn btn-success pull-left">Add New author</a>
                        <a href="../index.php" class="btn btn-success pull-left">Go back</a>
                    </div>
                    <?php
    // Attempt select query execution
      $sql = "SELECT * FROM authors";
      if($result = mysqli_query($connection, $sql)){
          if(mysqli_num_rows($result) > 0){
              echo "<table class='table table-bordered table-striped'>";
                  echo "<thead>";
                      echo "<tr>";
                          echo "<th>#</th>";
                          echo "<th>First name</th>";
                          echo "<th>Middle name	</th>";
                          echo "<th>Last name</th>";
                          echo "<th>Actions</th>";
                      echo "</tr>";
                  echo "</thead>";
                  echo "<tbody>";
                  while($row = mysqli_fetch_array($result)){
                      echo "<tr>";
                          echo "<td>" . $row['id'] . "</td>";
                          echo "<td>" . $row['first_name'] . "</td>";
                            echo "<td>" . $row['middle_name'] . "</td>";
                            echo "<td>" . $row['last_name'] . "</td>";
                          echo "<td>";
                              echo "<a href='readauthor.php?id=". $row['id'] ."' title='View Record' data-toggle='tooltip'><i class='fas fa-eye'></i></a>";
                              echo "<a href='updateauthor.php?id=". $row['id'] ."' title='Update Record' data-toggle='tooltip'><i class='fas fa-pen'></i></a>";
                              echo "<a href='deleteauthor.php?id=". $row['id'] ."' title='Delete Record' data-toggle='tooltip'><i class='far fa-trash-alt'></i></a>";
                          echo "</td>";
                      echo "</tr>";
                  }
                  echo "</tbody>";
              echo "</table>";
              // Free result set
              mysqli_free_result($result);
          } else {
              echo "<p class='lead'><em>No records were found.</em></p>";
          }
      } else{
          echo "ERROR: Could not able to execute $sql. " . mysqli_error($connection);
      }

      // Close connection
      mysqli_close($connection);
      ?>
    </div>















<?php include('../footer.php'); ?>
