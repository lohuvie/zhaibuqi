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
<p>Below is a list of all Activities. Use this page to remove activities as needed.</p>
<hr />

<?php
require_once('util.php');

// Connect to the database
$dbc = mysqli_connect(host,user, password, database);

// Retrieve the score data from MySQL
$query = "SELECT  * FROM activity ORDER BY activity_register_time desc";
$data = mysqli_query($dbc, $query);

// Loop through the array of score data, formatting it as HTML
echo '<table>';

while ($row = mysqli_fetch_array($data)) {
    // 显示信息
    $user_id = $row['user_id'];
    $query1 = "SELECT  * FROM user where user_id = $user_id ";
    $data1 = mysqli_query($dbc, $query1);
    $row1 = mysqli_fetch_array($data1);

    $activity_id = $row['activity_id'];
    $query2 = "SELECT  * FROM activity_photo where activity_id = $activity_id ";
    $data2 = mysqli_query($dbc, $query2);
    $row2 = mysqli_fetch_array($data2);
    echo '<tr><th>email</th><th>Date</th><th>title</th><th>Action</th><th>check</th></tr>';


    echo '<tr class="scorerow"><td><strong>' . $row1['email']//有待改进
        . '</strong></td>';
    echo '<td>' . $row['activity_register_time'] . '</td>';
    echo '<td>' .$row['name']. '</td>';

    echo '<td><a href="remove-activity.php?id=' . $row['activity_id'] . '&amp;date=' . $row['activity_register_time'] .
        '&amp;email=' . $row1['email'] . '&amp;title=' . $row['name']. '&amp;photo=' . $row2['photo'] .'">Remove</a>';

    if ($row['approved'] == '0') {
        echo ' / <a href="approve-activity.php?id=' . $row['activity_id'] . '&amp;date=' . $row['activity_register_time'] .
            '&amp;email=' . $row1['email'] . '&amp;title=' . $row['name'] .'&amp;place=' . $row['site'].'&amp;introduce='
            . $row['introduce'].'&amp;photo=' . $row2['photo'] .'">Approve</a>';
    }
    echo '</td>';
    echo'<td>' . '  <a href="../activity.php?activity=' . $row['activity_id']  .'">check</a></tr>';
}
echo '</table>';

mysqli_close($dbc);
?>

</body>
</html>
