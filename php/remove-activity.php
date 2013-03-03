<?php
  require_once('authorize.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Admin - Activities</title>
  <link rel="stylesheet" type="text/css" href="../css/style.css" />
</head>
<body>
  <h2>Admin - Activities</h2>

<?php
  require_once('util.php');

  echo 'id_'.$_GET['id'];
  echo 'date_'.$_GET['date'];
  echo 'email'.$_GET['email'];
  echo 'title'.$_GET['title'];
  echo 'photo'.$_GET['photo'];
  if (isset($_GET['id']) && isset($_GET['date']) && isset($_GET['email']) && isset($_GET['title'])&& isset($_GET['photo']) ) {
    // Grab the score data from the GET
    $id = $_GET['id'];
    $date = $_GET['date'];
    $email = $_GET['email'];
    $title = $_GET['title'];
    $photo = $_GET['photo'];


  }
  else if (isset($_POST['id']) && isset($_POST['email']) && isset($_POST['title'])) {
    // Grab the score data from the POST
    $id = $_POST['id'];
    $email = $_POST['email'];
    $title = $_POST['title'];
  }
  else {
    echo '<p class="error">Sorry, no activities was specified for removal.</p>';
  }

  if (isset($_POST['submit'])) {
    if ($_POST['confirm'] == 'Yes') {
      // Delete the screen shot image file from the server
      @unlink(UPLOADPATH . $photo);

      echo 'iddddddd_'.$id;
      // Connect to the database
      $dbc = mysqli_connect(host, user, password, database);

      //从activity_photo中删除
      $query = "DELETE FROM activity_photo WHERE activity_id = $id LIMIT 1";
      mysqli_query($dbc, $query);
      //从activity_photo中删除
      $query = "DELETE FROM activity_time WHERE activity_id = $id LIMIT 1";
      mysqli_query($dbc, $query);
      // 从activity中删除
      $query = "DELETE FROM activity WHERE activity_id = $id LIMIT 1";
      mysqli_query($dbc, $query);
      mysqli_close($dbc);

      // Confirm success with the user
      echo '<p>The activity of ' . $title . ' for ' . $email . ' was successfully removed.';
    }
    else {
      echo '<p class="error">The activity was not removed.</p>';
    }
  }
  else if (isset($id) && isset($email) && isset($date) && isset($title)) {
    echo '<p>Are you sure you want to delete the following activity?</p>';
    echo '<p><strong>Email: </strong>' . $email . '<br /><strong>Date: </strong>' . $date .
      '<br /><strong>Title: </strong>' . $title . '</p>';
    echo '<form method="post" action="removescore.php">';
    echo '<input type="radio" name="confirm" value="Yes" /> Yes ';
    echo '<input type="radio" name="confirm" value="No" checked="checked" /> No <br />';
    echo '<input type="submit" value="Submit" name="submit" />';
    echo '<input type="hidden" name="id" value="' . $id . '" />';
    echo '<input type="hidden" name="email" value="' . $email . '" />';
    echo '<input type="hidden" name="title" value="' . $title . '" />';
    echo '</form>';
  }

  echo '<p><a href="admin.php">&lt;&lt; Back to admin page</a></p>';
?>

</body> 
</html>
