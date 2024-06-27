let map;
let mapcanvas;
let drawingManager;
let paths_to_draw = [];

getMyLocation = () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const userLocation = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };
        map.setCenter(userLocation);
        const myLocation = new google.maps.LatLng(
          position.coords.latitude,
          position.coords.longitude
        );
        return myLocation;
      },
      (error) => {
        console.error("Error getting user location:", error);
      }
    );
  } else {
    console.error("Geolocation is not supported by this browser.");
  }
};

let current_map_location = getMyLocation();

async function initMap() {
  const { Map } = await google.maps.importLibrary("maps");

  map = new Map(document.getElementById("map"), {
    center: current_map_location,
    mapTypeId: google.maps.MapTypeId.HYBRID,
    zoom: 18,
  });

  // code to draw polygon
  drawingManager = new google.maps.drawing.DrawingManager({
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
      drawingModes: [google.maps.drawing.OverlayType.POLYGON],
    },
    polygonOptions: {
      strokeColor: "#FFFFFF",
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: "#FF0000",
      fillOpacity: 0.35,
      delete: true,
    },
  });

  // get coordinates after drawing polygon
  google.maps.event.addListener(drawingManager, "overlaycomplete", (event) => {
    if (event.type === google.maps.drawing.OverlayType.POLYGON) {
      const polygon = event.overlay;
      const coordinates = polygon.getPath().getArray();
      paths_to_draw.push(coordinates);
      displayCoordinates();
      geofenceArea.setPaths(paths_to_draw);
    }
  });

  // show marker in current location
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition((position) => {
      const marker = new google.maps.Marker({
        position: {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        },
        map: map,
        title: "Current Location",
      });
    });
  } else {
    console.log("Geolocation is not supported by this browser.");
  }

  // create geofence area
  const geofenceArea = new google.maps.Polygon({});

  // check if current location is inside geofence area
  isInsideGeofence = (point) => {
    console.log(geofenceArea);
    return google.maps.geometry.poly.containsLocation(point, geofenceArea);
  };

  drawingManager.setMap(map);
}

initMap();

clearMap = () => {
  paths_to_draw = [];
  displayCoordinates();
  initMap();
};

displayCoordinates = () => {
  $("#coordinates").val(JSON.stringify(paths_to_draw));
};

checkStatus = () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      (position) => {
        const userLocation = {
          lat: position.coords.latitude,
          lng: position.coords.longitude,
        };
        map.setCenter(userLocation);
        const pointToCheck = new google.maps.LatLng(
          position.coords.latitude,
          position.coords.longitude
        );
        if (isInsideGeofence(pointToCheck)) {
          showError("You are inside a field");
          // this part runs if user is in the geofence area
        }
      },
      (error) => {
        console.error("Error getting user location:", error);
      }
    );
  } else {
    console.error("Geolocation is not supported by this browser.");
  }
};

// data rendering starts from here
