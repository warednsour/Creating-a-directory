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

//Get the id of the author to return the list of Journals
function getJournalsList($id)
{

  //Definging $connection
  global $connection;
  //the journals
  $journals = [];
  //Query to select all the journals
    $sql = "SELECT * FROM journals";
  //executing the $sql and checking if it's executed
    if($result = mysqli_query($connection, $sql)){
      //Checking if there is a journals in the db
        if(mysqli_num_rows($result) > 0){
          //Fetching the data
            while($row = mysqli_fetch_array($result)){
              // if(count($row['author'] > 1))
              //This array will hold the values of the author ids from each journal
              //inserting the values to $authorsId as arrays,As We have journals that can contain more than one Author.
              $authorsId[] = explode(',',$row['author']);
              for($i = 0; $i < count($authorsId) ; $i++){
                //If the ID from author matches an ID from the journal Author then insert the value of that Journal to Array $journals.
                if($authorsId[$i][$i] == $id){
                  array_push($journals,$row['title']);
                }
              }

            }

          }
        }

//Return Array Journals
return $journals;
}
?>
