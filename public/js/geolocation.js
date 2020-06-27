        window.onload = function() {
        var startPos;
        var startPosLat;
        var startPosLong;
        var distance;

        document.getElementById('attendBtn').style.display = "none";
        document.getElementById('success').style.display = "none";
        document.getElementById('highlight').style.display = "none";
        if (navigator.geolocation) {

          startPosLat = 21.423092;
          startPosLong = 39.362932;

          // $("#startLat").text(startPosLat);
          // $("#startLon").text(startPosLong);

          navigator.geolocation.watchPosition(function(position) {
            // $("#currentLat").text(position.coords.latitude);
            // $("#currentLon").text(position.coords.longitude);
            // $("#currentAcc").text(position.coords.accuracy);

            distance = calculateDistance(startPosLat, startPosLong,position.coords.latitude, position.coords.longitude)
            // $("#distance").text(distance);

            // var GOOGLE_API = '{{ env('GOOGLE_MAPS_STATIC_API') }}';
            // var GOOGLE_API = '{!! env("GOOGLE_MAPS_STATIC_API") !!}';
            // console.log(GOOGLE_API);
            var latlon = position.coords.latitude + "," + position.coords.longitude;
            var img_url = "https://maps.googleapis.com/maps/api/staticmap?center="+latlon+"&zoom=16&scale=2&size=600x400&maptype=roadmap&key="+GOOGLE_API+"&format=png&visual_refresh=true&markers=size:small%7Ccolor:0x171faa%7Clabel:1%7C"+latlon;
            

            // it should be .10 later
            if(distance < .10){ 

              
              // $("#message").text("Yes, you're inside radius (100 Meters) 🎉");
              document.getElementById('spinner').style.display = "none";
              document.getElementById('highlight').style.display = "none";
              document.getElementById('success').style.display = "block";
              document.getElementById("success").innerHTML = "You are inside the allowed attendance area";
              document.getElementById('attendBtn').style.display = "block";
              document.getElementById("mapholder").innerHTML = "<img style='width: 100%;' src='"+img_url+"'>";
              document.getElementById("coords").innerHTML = "<input id='coords' type='text' name='coords' value='"+latlon+"' hidden />";
              // document.getElementById("coords").value = latlon;

            }else if(distance > .05){
              document.getElementById('attendBtn').style.display = "none";
              document.getElementById('highlight').style.display = "block";
              document.getElementById('success').style.display = "none";
              document.getElementById('tutorial').style.display = "none";
              document.getElementById("mapholder").innerHTML = "<img style='width: 100%;' src='"+img_url+"'>";
              document.getElementById("error").innerHTML = "You are outside the attendance enabled location";

            }
          },handleError);

          function handleError(error){
            //Handle Errors
           switch(error.code) {
              case error.PERMISSION_DENIED:
                  document.getElementById('spinner').style.display = "none";
                  document.getElementById('success').style.display = "none";
                  document.getElementById('highlight').style.display = "block";
                  document.getElementById("error").innerHTML = "You've denied the request for Geolocation (location services), please follow this instructions to allow the website to use your location. otherwise you will not be able to register your attendance.";
                  break;
              case error.POSITION_UNAVAILABLE:
                  console.log("Location information is unavailable.");
                  break;
              case error.TIMEOUT:
                 console.log("The request to get user location timed out.");
                  break;
              case error.UNKNOWN_ERROR:
                 console.log("An unknown error occurred.");
                  break;
          }
          }

        } else {
          alert("Your browser doesn't support Geolocation");
        }
      };

      // Reused code - copyright Moveable Type Scripts - retrieved May 4, 2010.
      // http://www.movable-type.co.uk/scripts/latlong.html
      // Under Creative Commons License http://creativecommons.org/licenses/by/3.0/
      function calculateDistance(lat1, lon1, lat2, lon2) {
        var R = 6371; // km
        var dLat = (lat2-lat1).toRad();
        var dLon = (lon2-lon1).toRad();
        var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1.toRad()) * Math.cos(lat2.toRad()) *
                Math.sin(dLon/2) * Math.sin(dLon/2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        var d = R * c;
        return d;
      }
      Number.prototype.toRad = function() {
        return this * Math.PI / 180;
      }
