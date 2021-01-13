<?php
require('../Config/functions.php');
include  '../header.php'
?>
<div class="container">
<div class="wrConfiger">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Journals Details</h2>
                        <a href="createJournal.php" class="btn btn-success pull-left">Add New Journal</a>
                        <a href="../index.php" class="btn btn-success pull-left">Go back</a>
                    </div>
                    <?php
    // Attempt select query execution
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
                          echo "<th>Action</th>";
                      echo "</tr>";
                  echo "</thead>";
                  echo "<tbody>";
                  while($row = mysqli_fetch_array($result)){
                      echo "<tr>";
                          echo "<td>" . $row['id'] . "</td>";
                          echo "<td>" . $row['title'] . "</td>";
                            echo "<td>" . $row['description'] . "</td>";
                            $author = getAuthors($row['author']);
                              echo "<td>" ;
                           for($i= 0;$i < count($author); $i++){
                                  echo  $author[$i] . "<br>" ;
                                }
                            echo "</td>";
                          echo "<td>" ."<img style='width:50%;' src = '../images/" .  $row['image'] . "'>" . "</td>";
                          echo "<td>" . $row['release_date'] . "</td>";
                          echo "<td>";
                              echo "<a href='readJournal.php?id=". $row['id'] ."' title='View Record' data-toggle='tooltip'><i class='fas fa-eye'></i></a>";
                              echo "<a href='updateJournal.php?id=". $row['id'] ."' title='Update Record' data-toggle='tooltip'><i class='fas fa-pen'></i></a>";
                              echo "<a href='deleteJournal.php?id=". $row['id'] ."' title='Delete Record' data-toggle='tooltip'><i class='far fa-trash-alt'></i></a>";
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
