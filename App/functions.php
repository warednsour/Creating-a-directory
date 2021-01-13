<?php

require_once('db.php');

function getAuthor($id) {


  $sql = "SELECT * FROM `authors` WHERE id = ?";
  global $connection;
  if($stmt = mysqli_prepare($connection, $sql)){
      // // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "i", $param_id);

      // Set parameters
      $param_id = $id;

      // Attempt to execute the prepared statement
      if(mysqli_stmt_execute($stmt)){
          $result = mysqli_stmt_get_result($stmt);

          if(mysqli_num_rows($result)){

            /* Fetch result row as an associative array.
             Since the result set contains only one row,
              we don't need to use while loop */
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $author = '';
            $author .= $row['first_name'];
            $author .= ' ';
            $author .= $row['middle_name'];
            $author .= ' ';
            $author .= $row['last_name'];
            return  "$author";
          }
          // Close statement
          mysqli_stmt_close($stmt);
        }
      } else {
          echo "ERROR: Could not able to execute" . mysqli_error($connection);
      }
  }


function getAuthors($id)
{
    $authors = [];
    //String to array
    $exploded = explode(',',$id);

      foreach ($exploded as $ex) {
        array_push($authors,getAuthor($ex));
      }

    return $authors;

}

function getJournal($id)
{
  global $connection;
  $sql = "SELECT * FROM journals WHERE id = ?";
  if($stmt = mysqli_prepare($connection,$sql)){
    // // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $param_id);

    // Set parameters
    $param_id = $id;

    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result)){
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            return $row;
          }
          // Close statement
          mysqli_stmt_close($stmt);
      }
    }
    echo "ERROR: Could not able to execute" . mysqli_error($connection);
}

?>
