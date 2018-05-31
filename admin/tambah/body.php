<link rel="stylesheet" href="../assets/css/autocomplete.css">
<script>
    // This example requires the Places library. Include the libraries=places
    // parameter when you first load the API. For example:
    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            center: {
                lat: -7.36,
                lng: 109.9025
            },
            zoom: 12
        });
        var input = /** @type {!HTMLInputElement} */ (
            document.getElementById('pac-input'));

        var types = document.getElementById('type-selector');
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow();
        var marker = new google.maps.Marker({
            map: map,
            anchorPoint: new google.maps.Point(0, -29)
        });

        autocomplete.addListener('place_changed', function() {
            infowindow.close();
            marker.setVisible(false);
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                // User entered the name of a Place that was not suggested and
                // pressed the Enter key, or the Place Details request failed.
                window.alert("No details available for input: '" + place.name + "'");
                return;
            }

            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17); // Why 17? Because it looks good.
            }
            marker.setIcon( /** @type {google.maps.Icon} */ ({
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(35, 35)
            }));
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);
            
            var item_Lat = place.geometry.location.lat();
            var item_Lng = place.geometry.location.lng();
            var item_Alamat = place.formatted_address;   
            
            $("#lat").val(item_Lat);
            $("#lng").val(item_Lng);
            $("#alamat").val(item_Alamat);

            var address = '';
            if (place.address_components) {
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
            }

            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
            infowindow.open(map, marker);
        });

    }
</script>

<?php
    include "../config.php";

if(isset($_POST['submit'])) {
    
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $query = "INSERT INTO tb_daftar_toko (nama, alamat, lat, lng)
                VALUES ('$nama', '$alamat', '$lat', '$lng')";
    if (mysql_query ($query)) {
        echo "<div> class='alert alert-success'>Data berhasil di simpan</div>";
    }
    
}
?>

<div class="content-wrapper">
    <div class="container-fluid">
       <form method="POST" action="">
        <div class="row">
            <div class="col-12">
               

                <input type="text" name="nama" placeholder="Nama Toko" class="form-control">
                <br>
                <input type="submit" name="submit" value="Simpan" class="btn btn-primary float-right">
                <br>
                <input type="hidden" name="alamat" id="alamat">
                <input type="hidden" name="lat" id="lat">
                <input type="hidden" name="lng" id="lng">

                <input id="pac-input" class="controls" type="text" placeholder="Enter a location">
                <div id="type-selector" class="controls">
                    <input type="radio" name="type" id="changetype-all" checked="checked">
                    <label for="changetype-all">All</label>
                </div>
                <div id="map" style="width: 100%;height: 470px"></div>
            </div>
        </div>
        </form>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-JpweDJ7_cA9-KiEq-iMjQzlluOemnWo&libraries=places&callback=initMap" async defer></script>