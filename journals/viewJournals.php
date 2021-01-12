<?php
require_once('../db.php');
include  '../header.php'
?>
<div class="container">
<div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Journals Details</h2>
                        <a href="createJournal.php" class="btn btn-success pull-right">Add New Employee</a>
                    </div>
<?php // Attempt select query execution
      $sql = "SELECT * FROM journals";
      if($result = mysqli_query($connection, $sql)){
          if(mysqli_num_rows($result) > 0){
              echo "<table class='table table-bordered table-striped'>";
                  echo "<thead>";
                      echo "<tr>";
                          echo "<th>#</th>";
                          echo "<th>title</th>";
                          echo "<th>description	</th>";
                          echo "<th>author</th>";
                          echo "<th>image</th>";
                          echo "<th>Relase Date</th>";
                      echo "</tr>";
                  echo "</thead>";
                  echo "<tbody>";
                  while($row = mysqli_fetch_array($result)){
                      echo "<tr>";
                          echo "<td>" . $row['id'] . "</td>";
                          echo "<td>" . $row['title'] . "</td>";
                            echo "<td>" . $row['description'] . "</td>";
                          echo "<td>" . $row['author'] . "</td>";
                          echo "<td>" . $row['image'] . "</td>";
                          echo "<td>" . $row['relase_date'] . "</td>";
                          echo "<td>";
                              echo "<a href='read.php?id=". $row['id'] ."' title='View Record' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                              echo "<a href='update.php?id=". $row['id'] ."' title='Update Record' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                              echo "<a href='delete.php?id=". $row['id'] ."' title='Delete Record' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                          echo "</td>";
                      echo "</tr>";
                  }
                  echo "</tbody>";
              echo "</table>";
              // Free result set
              mysqli_free_result($result);
          } else{
              echo "<p class='lead'><em>No records were found.</em></p>";
          }
      } else{
          echo "ERROR: Could not able to execute $sql. " . mysqli_error($connection);
      }

      // Close connection
      mysqli_close($connection);
      ?>
    </div>
<?php include  '../footer.php'?>
