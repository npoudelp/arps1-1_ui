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

  google.maps.event.addListener(drawingManager, "overlaycomplete", (event) => {
    if (event.type === google.maps.drawing.OverlayType.POLYGON) {
      const polygon = event.overlay;
      const coordinates = polygon.getPath().getArray();
      paths_to_draw.push(coordinates);
      displayCoordinates();
      geofenceArea.setPaths(paths_to_draw);
    }
  });

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

  isInsideGeofence = (point) => {
    console.log(point);
    console.log(paths_to_draw);
    return google.maps.geometry.poly.containsLocation(point, geofenceArea);
  };

  const marker = new google.maps.Marker({
    position: current_map_location,
    map: map,
    title: "My Location",
  });

  drawingManager.setMap(map);
}

initMap();

clearMap = () => {
  initMap();
  paths_to_draw = [];
  displayCoordinates();
};

displayCoordinates = () => {
  $("#coordinates").text(JSON.stringify(paths_to_draw));
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
          alert("You are inside the geofence area");
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
