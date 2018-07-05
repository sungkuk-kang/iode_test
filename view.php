<?php

if(!isset($_GET['id']) || ($id = intval($_GET['id'])) == 0)
{
    header("Location: index.php");
    exit();
}

$db = 'project';
include '.htinclude.php';

$result = mysqli_query($db, 'SELECT scientificname, longitude, latitude, `user_id` FROM observations WHERE id = ' . $id);

if($result === false)
{
    mysqli_close($db);
    exit('<meta charset="utf-8" /><script>alert("Database error."); location.href="index.php";</script>');
}

if(mysqli_num_rows($result) == 0)
{
    mysqli_close($db);
    exit('<meta charset="utf-8" /><script>alert("Cannot find the data."); location.href="index.php";</script>');
}

$row = mysqli_fetch_assoc($result);

/* --------- Providing 'Delete' and 'Edit' access to the users who logged in --------- */
if(isset($_SESSION['id'])){
echo '<meta charset="utf-8" />' . ( ($_SESSION['id'] == $row['user_id']) ? ( 
'<a href="delete.php?id=' . $id . '">Delete</a>' . ' / <a href="modify.php?id=' . $id . '">Edit</a><br />' ): '<br></br>' );
}

/* --------- Disply scientific information from Database --------- */
echo '<br><meta charset="utf-8" />Scientificname : <b>' . htmlspecialchars($row['scientificname']) . '</b><hr />'.'Longitude : <b>' . htmlspecialchars($row['longitude']) . '</b><hr />'.'Latitude : <b>' . htmlspecialchars($row['latitude']) . '<hr /><br><a href="index.php">Go to List</a>';

echo '
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<center>
<div id="map" style="height: 400px; width: 1000px;">
</div>
</center>


<script type="text/javascript">';
/* ------------------- Display scientific information on the map ---------------- */
    echo'    
    var locations = [
      [\'' . htmlspecialchars($row['scientificname']) .'\',' . htmlspecialchars($row['latitude']) . ','. htmlspecialchars($row['longitude']) . '],
    ];';
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