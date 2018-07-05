<?php
$db = 'project';
include '.htinclude.php'; // DB connection information

echo'<div style="text-align:center">';
echo'
<head>
<style type="text/css">
td {
  color: #004de6;
}
</style>
</head>';
echo '<meta charset="utf-8" />' . ( $login ? ('Welcome ' . $_SESSION['username'] .
'!<br /><a href="logout.php">Log out</a>' . ' / <a href="write.html">Write</a>' ): '<a href="login.html">Login</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="register.html">Register</a>'); // Log in/out field

$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
if($offset < 0) $offset = 0;

$query = 'SELECT A.id id, A.scientificname scientificname, A.longitude longitude, A.latitude latitude, A.`date` `date`, U.username username FROM observations A INNER JOIN `users` U ON A.`user_id` = U.id ORDER BY A.id DESC LIMIT 10 OFFSET
    ' . $offset;
// SQL query to get the data from observations and users table, using inner join

$result = mysqli_query($db, $query);
$num_record = mysqli_num_rows($result);

/* ---------- Create a table and disply scientific information from Database ---------- */
if ($num_record != 0)
{
    echo '<br></br>';
    echo '<table border="1" align="center">';
    echo '<tr bgcolor="#ffccff">
    <th>scientificname</th>
    <th>longitude</th>
    <th>latitude</th>
    <th>date</th>
    </tr>';
    $item1 = array();
    $item2 = array();
    $item3 = array();

    $i = 0;
    while($row = mysqli_fetch_assoc($result))
    {
        echo '<tr data-id="' . $row['id']. '">
        <td>' . htmlspecialchars($row['scientificname']) . '</td>
        <td>' . htmlspecialchars($row['longitude']) . '</td>
        <td>' . htmlspecialchars($row['latitude']) . '</td>
        <td>' . $row['date'] . '</td></tr>';

    $sc_name[$i] = htmlspecialchars($row['scientificname']);
    $lo_tude[$i] = htmlspecialchars($row['longitude']);
    $la_tude[$i] = htmlspecialchars($row['latitude']);
    $i = $i+1; 
    }

    echo '</table>';
/* ---------------------------------------------------------------------------- */

    echo '<script>(function (){ var onclick = function (ev){ var target = ev.target || ev.srcElement; location.href = "view.php?id=" + (target.dataset.id || target.parentNode.dataset.id); }; var el = document.querySelectorAll("tr[data-id]"); var len = el.length; for(var i = 0; i < len; i++) { el[i].onclick = onclick; } })();</script>';
    echo '<p>';
    if($offset != 0) echo '<a href="?offset=' . ($offset > 10 ? $offset - 10 : 0) . '">◀ Previous</a> ';
    echo '▲ From Index '. ($offset + 1) . ' to Index ' . ($offset + $num_record);
    if($num_record == 10) echo ' <a href="?offset=' . ($offset + 10) . '">Next ▶</a></p>';
    echo '</p>';
}
else
{
    echo "<p>Content empty!</p>";
    echo '<a href="?offset=' . ($offset > 10 ? $offset - 10 : 0) . '"> Back</a>';
}
echo'</div>';
?>

<?php
/* ------------------- Display scientific information on the map ---------------- */
echo'
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<center>
<div id="map" style="height: 400px; width: 1000px;">
</div>
</center>
<script type="text/javascript">';

/* Display the scientific information (scientificname, longitude and latitude) using 'for loops' */
    $arrlength = count($sc_name);
    echo'
    var locations = [';
    for ($x = 0; $x < $arrlength; $x++){
    echo '[\'' . $sc_name[$x] .'\',' . $la_tude[$x] . ','. $lo_tude[$x] . '],';
    } 
    echo' ];';
/* ------------------------------------------------------------------------------- */

    echo'
    var map = new google.maps.Map(document.getElementById(\'map\'), {
      zoom: 8,
      center: new google.maps.LatLng(51.3501, 2.7047),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) { 
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map
      });

      google.maps.event.addListener(marker, \'click\', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
</script>
';

mysqli_close($db);
?>