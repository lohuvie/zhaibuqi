<?php
  require_once ('authorize.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Admin - Activities </title>
  <link rel="stylesheet" type="text/css" href="../css/style.css" />
</head>
<body>
  <h2>Admin - Activities</h2>

<?php
  require_once('util.php');

  if (isset($_GET['id']) && isset($_GET['date']) && isset($_GET['email']) && isset($_GET['title'])&&
      isset($_GET['photo'])&& isset($_GET['place'])&& isset($_GET['introduce'])) {
    // Grab the score data from the GET
    $id = $_GET['id'];
    $date = $_GET['date'];
    $email = $_GET['email'];
    $title = $_GET['title'];
    $place=$_GET['place'];
    $introduce=$_GET['introduce'];

    $photo= $_GET['photo'];
  }
  else if (isset($_POST['id']) && isset($_POST['email']) && isset($_POST['title'])) {
    // Grab the score data from the POST
    $id = $_POST['id'];
    $email = $_POST['email'];
    $title = $_POST['title'];
  }
  else {
    echo '<p class="error">Sorry, no activity was specified for approval.</p>';
  }

  if (isset($_POST['submit'])) {
    if ($_POST['confirm'] == 'Yes') {
      // Connect to the database
      $dbc = mysqli_connect(host, user, password, database);

      // Approve the score by setting the approved column in the database
      $query = "UPDATE activity SET approved = 1 WHERE activity_id = $id";
      mysqli_query($dbc, $query);
      mysqli_close($dbc);

      // Confirm success with the user
      echo '<p>The activity  of ' . $email . ' for ' . $title . ' was successfully approved.';
    }
    else {
      echo '<p class="error">Sorry, there was a problem approving the activity.</p>';
    }
  }
  else if (isset($id) && isset($title) && isset($date) && isset($email)) {
    echo '<p>Are you sure you want to approve the following activity?</p>';
    echo '<p><strong>Email: </strong>' . $email . '<br /><strong>Date: </strong>' . $date .
      '<br /><strong>Title: </strong>' . $title .'<br/>' .'<strong>Place: </strong>' . $place . '<br />
      <strong>Introduce: </strong>' . $introduce . '<br /></p>';
    echo '<form method="post" action="approvescore.php">';
    echo '<img src="' .UPLOADPATH . $photo . '" width="160" alt="Activity image" /><br />';
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
