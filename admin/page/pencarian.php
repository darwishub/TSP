<?php
	session_start();
    
?>

<?php
  include "database/db_toko.php";
?>
        <script>
            var marker;
            var map;

            function initialize() {
                var mapCanvas = document.getElementById('map-canvas');
                var mapOptions = {
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }

                var map = new google.maps.Map(mapCanvas, mapOptions);

                var infoWindow = new google.maps.InfoWindow;
                var bounds = new google.maps.LatLngBounds();


                function bindInfoWindow(marker, map, infoWindow, html) {
                    google.maps.event.addListener(marker, 'click', function() {
                        infoWindow.setContent(html);
                        infoWindow.open(map, marker);
                    });
                }

                function addMarker(lat, lng, info) {
                    var pt = new google.maps.LatLng(lat, lng);
                    bounds.extend(pt);
                    var marker = new google.maps.Marker({
                        map: map,
                        position: pt
                    });
                    map.fitBounds(bounds);
                    var listener = google.maps.event.addListener(map, "idle", function() {
                        map.setZoom(12);
                        google.maps.event.removeListener(listener);
                    });
                    bindInfoWindow(marker, map, infoWindow, info);
                }

                <?php
            $query = mysql_query("select * from tb_daftar_toko");
          while ($data = mysql_fetch_array($query)) {
            $lat = $data['lat'];
            $lon = $data['lng'];
            $nama = $data['nama'];
            echo ("addMarker($lat, $lon, '<b>$nama</b>');\n");                        
          }
          ?>
            }

            google.maps.event.addDomListener(window, 'load', initialize);
        </script>

        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- Nav tabs-->
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Tujuan Toko</a>
                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Maps</a>
                            </div>
                        </nav>
                        <!--Nav Content -->
                        <div class="tab-content" id="nav-tabContent">

                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">.s.</div>


                            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                <div id="map-canvas" style="width: 100%;height: 470px"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-JpweDJ7_cA9-KiEq-iMjQzlluOemnWo&callback=initialize" type="text/javascript"></script>