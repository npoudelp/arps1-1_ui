let map;
let mapcanvas;
let drawingManager;
let paths_to_draw = [];
let available_fields = [];

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

  map = new Map(document.getElementById("mapDashboard"), {
    center: current_map_location,
    mapTypeId: google.maps.MapTypeId.HYBRID,
    zoom: 18,
  });
  // from other page
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

  // create geofence area
  const geofenceArea = new google.maps.Polygon({});

  // check if current location is inside geofence area
  isInsideGeofence = (point) => {
    console.log(geofenceArea);
    return google.maps.geometry.poly.containsLocation(point, geofenceArea);
  };

  drawingManager.setMap(map);

  // other code ends here

  plotMap = (coordinates, id, name, nitrogen, phosphorus, potassium, crop) => {
    paths_to_draw.push(coordinates);

    const values = coordinates.match(/{(.*?)}/g);
    const coordinates_array = values.map((value) => {
      return {
        lat: parseFloat(value.match(/(\d+\.\d+)/g)[0]),
        lng: parseFloat(value.match(/(\d+\.\d+)/g)[1]),
      };
    });

    const polygon_coordinates = coordinates_array;

    const plot = new google.maps.Polygon({
      paths: polygon_coordinates,
      strokeColor: "#FF0000",
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: "#FF0000",
      fillOpacity: 0.35,
    });

    coordinates_array.push(id, name, nitrogen, phosphorus, potassium, crop);
    available_fields.push(coordinates_array);

    plot.setMap(map);

    //sets name for polygon
    const infoWindow = new google.maps.InfoWindow({
      content: name,
    });

    plot.addListener("click", function (event) {
      // Handle click event
      infoWindow.setPosition(event.latLng);
      infoWindow.open(map, plot);
      $("#field_display").show();
      $("#coordinates").val(coordinates);
      $("#name").val(name);
      $("#nitrogen").val(nitrogen);
      $("#phosphorus").val(phosphorus);
      $("#potassium").val(potassium);
      $("#crop").val(crop);
      $("#update").val(id);

      console.log(name, event.latLng);
    });

    paths_to_draw = [];
  };

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
}

clearMap = () => {
  paths_to_draw = [];
  $("#coordinates_value").val(JSON.stringify(paths_to_draw));
  initMap();
};

displayCoordinates = () => {
  $("#field_display").hide();
  $("#fieldAddForm").show();
  const flattenedPaths = paths_to_draw.flat();
  $("#coordinates_value").val(JSON.stringify(flattenedPaths));
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

// data rendering starts from here

// request for fields
getAllFields = () => {
  const base_url = "http://127.0.0.1:8000/";
  $.ajax({
    url: base_url + "api/field/get/",
    type: "GET",
    headers: {
      Authorization: "Bearer " + localStorage.getItem("access_token"),
    },
    success: function (response, status, xhr) {
      response.forEach((field) => {
        plotMap(
          field.coordinates,
          field.id,
          field.name,
          field.nitrogen,
          field.phosphorus,
          field.potassium,
          field.crop
        );
      });
    },
    error: function (error) {
      console.log(error);
    },
  });
};

$(document).ready(function () {
  initMap();
  getAllFields();
  console.log(available_fields);
});
