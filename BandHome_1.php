<?php
// require_once('save_forms.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="css/forms1.css" type="text/css"  />
        <title>Band Home Page</title>

        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>

        <script type="text/javascript" src="http://code.google.com/apis/gears/gears_init.js"></script>
 <script type="text/javascript">
      var map;
var baricon="pictures/loc.png" ;//"http://labs.google.com/ridefinder/images/mm_20_blue.png";
 /*   var customIcons = {
      restaurant: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
      },
      bar: {
        icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
      }
    };
    */
var myLatlng = new google.maps.LatLng(39.55183886296252, -99.6429705619812);
    function load(pbandid) {
       map = new google.maps.Map(document.getElementById("map_canvas"), {
        center: myLatlng,
        zoom: 3,
        mapTypeId: 'roadmap'
      });

      var infoWindow = new google.maps.InfoWindow;



  var pageRequest=false

 var xml ="dude";
 var markers;
try{
    var url="ex.xml";
    url="test.php";


      // downloadUrl("test1.php", function(data) {
       downloadUrl("getbandmap.php?bandid="+pbandid, function(data) {
        var xml = data.responseXML;
 var markers = xml.documentElement.getElementsByTagName("marker");

 for (var i = 0; i < markers.length; i++) {

          var point = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng")));
          var when = markers[i].getAttribute("when");
          var address = markers[i].getAttribute("address");
          var notes = markers[i].getAttribute("notes");
var html = "<b>When:</b>" + when + "<br/>" + "<b>Address:</b>" + address + " <br/><b>Notes:</b>" +notes;
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            icon: baricon

          });
           bindInfoWindow(marker, map, infoWindow, html);


        }
   });

 }
 catch (e){
     document.getElementById("error").innerHTML="error = "+ e.message;
     }


    }

    function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }

function downloadUrl(url,callback) {
 var request = window.ActiveXObject ?
     new ActiveXObject('Microsoft.XMLHTTP') :
     new XMLHttpRequest;

 request.onreadystatechange = function() {
   if (request.readyState == 4) {
     request.onreadystatechange = doNothing;
     callback(request, request.status);
   }
 };

 request.open('GET', url, true);
 request.send(null);
}

    function doNothing() {  alert("javacript is do nothing crapfucked!");}



            function direction(direction){
                //  document.getElementById('_latitude').value=direction;
                if (direction=='L'){
                    map.panBy(-50, 0);
                }
                if (direction=='R'){
                    map.panBy(50, 0);
                }
                if (direction=='U'){
                    map.panBy(0, -50);
                }
                if (direction=='D'){
                    map.panBy(0, 50);
                }
                /**/
            }

        </script>

    </head>
    <body>
        <div class="frame">
            <?php include_once 'menu_generic.php' ;
                if (isset($_REQUEST['errors'])&& $_REQUEST['errors'] !="")
                    echo "Errors=".$_REQUEST['errors'];
               if ( isset($_SESSION['uid']) && isset($_REQUEST['bandid'])) {

                    $bandid=$_REQUEST['bandid'];
                    $ret=getBandDetails($bandid);
                        echo "<h2>".  $ret['bandname']."</h2>";

                 }
                else
                    echo "<td><a href='login.php'>Login to see your band's page</a></td></tr>";
            ?>

            <!--begin details
            -->
            <div class="bandetails">
                <div class='bd_left'>

                <?php

                    if ($ret) {

                        echo "" ;
                        echo  $ret['genre']."<br />";
                        echo  "<div class='fullnotes'>".$ret['notes']."</div><br />";
                        echo  $ret['weblink']."<br />";
                        echo  $ret['myspacelink']."<br />";
                        echo  $ret['facebooklink']."<br />";                 
                ?>
                </div>
                <div class="bd_right">
                            <?php
                             $pic=$ret['filename'];
                            if ((isset($bandid)) && (isset($pic))) {
                                if ($pic !="") {
                                    echo "<img src='pictures/".$pic."' height='150' width='150' />";
                                }
                            }
                            ?>
                </div><br />



            </div>
            <div class="midbreak">
            <br />

            <a href="frmBand.php?editband=1&bandid=<?php echo $bandid ;?>">Edit this band's details </a>

            <br />

            <br />
            </div>
            <a name="status"></a>
            
            <form action="save_forms.php" method="post">
                <label><b>Update Status Now !</b></label><br />
                <input type="submit" name="updatestatus" value="Add"/><br />
                <input type="hidden" name="bandid" value="<?php echo $bandid; ?>"/>
                <input type="hidden" name="statustype" value="b"/>
                <input type="text" name="status" maxlength="200" size="100"  />

            </form>

            <br />
           
            <br />
            <!--end details> -->
            <div class="mid1">
                <h2>Band Messages</h2>
                        <?php

                        $messages=getBandMessages();
                        if (isset($messages)) {
                            ?>



                <table class="table" >
                    <tr><th>Message Date</th><th>Subject</th><th>From </th>
                       <!-- <th>Note</th><th>Contact Name</th></tr> -->
                                    <?php
                                    // put your code here
                                    //      $messages=getBandMessages();
                                    //      if (isset($messages)){
                                    $i=0 ;
                                    $nl=false;
                                    $messageid=0;
                                    $sendid="";
                                    $mdate="";
                                    $subject="";
                                    $contact_name="";

                                    foreach($messages as $ret) {

                                        if (key($ret)=="messageid")
                                            $messageid=  current($ret);

                                        if (key($ret)=="fromid")
                                            $sendid=  current($ret);

                                        if (key($ret)=="mdate") {
                                            $mdate=  current($ret);
                                            echo "<tr>";
                                        }
                                        if (key($ret)=="subjectt")
                                            $subject=  current($ret);

                                        if (key($ret)=="contact_name") {
                                            $contact_name=  current($ret);
                                            echo "</tr>";
                                            $nl=true;
                                        }
                                        if ($nl==true) { ?>
                    <tr><td><?php echo $mdate; ?></td><td><?php echo $subject ; ?></td><td><?php echo "<a href='viewmessage.php?messageid=$messageid&prevtype='b''>$contact_name</a>" ; ?></td></tr>
                                        <?php $nl=false;}
                                }
                                ?>
                </table>


                        <?php }
                        else
                            echo "<h3>No Messages</h3>";
                        ?>
            </div><br />


            <!--end bandmessages -->
            <br /> <br />
            <a name="tourdetails"></a>
            <!--begin tourneeds-->
            <div class="mid1">
                <h2>When Touring </h2>
                        <?php
                        $rows=getBandSpotsNeeded($bandid);
                        if ($rows) {
                            ?>

                <table class="table">
                    <tr><th>When Needed</th><th>Need<br />Gig </th><th>  spots<br />needed </th><th>  city </th><th>State</th><th>  Country </th><th>Notes</th></tr>

                                <?php

                                $nl=false;
                                $city="";
                                $when="";
                                $spots="";
                                $state="";
                                $country="";
                                $notes="";
                                $needgig="";
                                $mappoint="";
                                foreach($rows as $ret) {

                                // foreach($retr as $ret){

                                    if (key($ret)=="mappoint")
                                        $mappoint=  current($ret);

                                    if (key($ret)=="city")
                                        $city=  current($ret);
                                    if (key($ret)=="when") {
                                        $when= current($ret);
                                        echo "<tr>";
                                    }
                                    if (key($ret)=="spots")
                                        $spots= current($ret);
                                    if (key($ret)=="notes") {
                                        $notes= current($ret);
                                        echo "</tr>";
                                        $nl=true;
                                    }
                                    if (key($ret)=="state")
                                        $state= current($ret);
                                    if (key($ret)=="country")
                                        $country=current($ret);
                                    if (key($ret)=="needgig")
                                        $needgig= current($ret);
                                    if ($nl==true) { ?>
                    <tr><td><?php echo $when ; ?></td><td><?php echo $needgig ; ?></td><td><?php echo $spots ; ?></td><td><?php echo $city ; ?></td><td><?php echo $state ; ?></td><td><?php echo $country ; ?></td><td><?php echo $notes ; ?></td></tr>
                                        <?php
                                        $nl=false;

                                        if ($mappoint !="") {
                                            ?>
                                            <?php echo "<script>addMarker".$mappoint.";</script>"; ?>


                                        <?php }
                                    }
                                }
                                ?>


                </table><br /><br />
                <div id="nav" style="width:60%;margin-left:auto;margin-right:auto;">
                    <label>map navigator</label>
                    <input type="button" id="l"  value ="left" onclick="direction('L');">

                    <input type="button" id="r"  value ="right" onclick='javascript:direction("R");'>
                    <input type="button" id="u"  value ="up" onclick='javascript:direction("U");'>
                    <input type="button" id="d"  value ="down" onclick='javascript:direction("D");'>

                </div>
                <div id="map_canvas" style="width: 500px; height :300px">

                </div>
                        <?php }
                        else
                            echo "<h3> No tour info defined</h3>";
                        ?>

                <br /><p style="text-align:center;"> </p>

            </div>
            <!-- end tourneeds -->
            <!-- begin statusupdate -->


            <!-- end tourneeds -->
                <?php 
            } // end of check for band sessionid
 

            ?>
            <br /> <br />

            <div id="map_canvas2" style="width: 500px; height :300px">
            </div>
        </div>
    </body>
</html>
