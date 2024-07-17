let map;
let mapcanvas;
let drawingManager;
let paths_to_draw = [];
let available_fields = [];
let field_ids = [];
let lastPolygon = null;

async function initMap() {
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

  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
      // Cancel the drawing
      if (drawingManager) {
        drawingManager.setDrawingMode(null);
        paths_to_draw = [];
      }
      // Remove the last drawn polygon from the map
      if (lastPolygon) {
        lastPolygon.setMap(null);
        lastPolygon = null;
        $("#coordinates_value").val("");
        $("#fieldAddForm").hide();
      }
    }
  });

  // get coordinates after drawing polygon
  google.maps.event.addListener(drawingManager, "overlaycomplete", (event) => {
    if (event.type === google.maps.drawing.OverlayType.POLYGON) {
      const polygon = event.overlay;
      const coordinates = polygon.getPath().getArray();
      paths_to_draw.push(coordinates);
      displayCoordinates();

      lastPolygon = polygon;
    }
  });

  drawingManager.setMap(map);

  // other code ends here

  plotMap = (
    coordinates,
    id,
    name,
    nitrogen,
    phosphorus,
    potassium,
    crop,
    ph
  ) => {
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

    coordinates_array.push(id, name, nitrogen, phosphorus, potassium, crop, ph);
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
      $("#ph").val(ph);
    });

    paths_to_draw = [];
  };

  // check if current location is inside geofence area
  isInsideGeofence = (point) => {
    const geofenceArea = new google.maps.Polygon({});
    let latLng = new google.maps.LatLng(point.lat, point.lng);
    let index = 0;
    return available_fields.some((field) => {
      const values = field.match(/{(.*?)}/g);
      const coordinates_array = values.map((value) => {
        return {
          lat: parseFloat(value.match(/(\d+\.\d+)/g)[0]),
          lng: parseFloat(value.match(/(\d+\.\d+)/g)[1]),
        };
      });
      geofenceArea.setPath(coordinates_array);
      if (google.maps.geometry.poly.containsLocation(latLng, geofenceArea)) {
        viewLiveDetails(field_ids[index]);
        // console.log(field_ids[index]);
        return true;
      }
      index++;
    });
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
          available_fields.push(field.coordinates);
          field_ids.push(field.id);
          plotMap(
            field.coordinates,
            field.id,
            field.name,
            field.nitrogen,
            field.phosphorus,
            field.potassium,
            field.crop,
            field.ph
          );
        });
      },
      error: function (error) {
        console.log(error);
      },
    });
  };

  viewLiveDetails = (id) => {
    let farm_name = "";
    const base_url = "http://127.0.0.1:8000/";
    $.ajax({
      url: base_url + "api/field/get/id/" + id + "/",
      type: "GET",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("access_token"),
      },
      success: function (response) {
        $("#update").hide();
        $("#fieldAddForm").hide();
        $("#coordinates").val(response.coordinates);
        $("#name").val(response.name);
        farm_name = response.name;
        $("#update").val(response.id);
        $("#crop").val(response.crop);
        $("#nitrogen").val(response.nitrogen);
        $("#phosphorus").val(response.phosphorus);
        $("#potassium").val(response.potassium);
        $("#ph").val(response.ph);
        $("#loading").hide();
        $("#field_display").show();
      },
    });

    $.ajax({
      url: base_url + "api/field-activities/all/" + id + "/",
      type: "GET",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("access_token"),
      },
      success: function (response) {
        $("#history_title").empty();
        $("#history_title").text("History of farm, " + farm_name);
        if (response["plantation"]) {
          let plantation = response["plantation"];
          let html =
            "<table class='table table-striped'><tr><th>Plantation Date</th><th>Crop</th></tr>";
          plantation.forEach((item) => {
            let dt = item.date.replace("T", ", ");
            dt = dt.split(".")[0];
            html +=
              "<tr><td>" + dt + "</td><td>" + item.crop + "</td></tr>";
          });
          html += "</table>";
          $("#plantation").html(html);
        }
        if (response["fertilizer"]) {
          let fertilizer = response["fertilizer"];
          let html =
            "<table class='table table-striped'><tr><th>Fertilized Date</th><th>Name</th><th>Quantity</th></tr>";
          fertilizer.forEach((item) => {
            let dt = item.date.replace("T", ", ");
            dt = dt.split(".")[0];
            html +=
              "<tr><td>" +
              dt +
              "</td><td>" +
              item.name +
              "</td><td>" +
              item.quantity +
              "</td></tr>";
          });
          html += "</table>";
          $("#fertilizer").html(html);
        }
        if (response["irrigation"]) {
          let irrigation = response["irrigation"];
          let html =
            "<table class='table table-striped'><tr><th>Irrigation Date</th><th>Type</th></tr>";
          irrigation.forEach((item) => {
            let dt = item.date.replace("T", ", ");
            dt = dt.split(".")[0];
            html +=
              "<tr><td>" + dt + "</td><td>" + item.type + "</td></tr>";
          });
          html += "</table>";

          $("#irrigation").html(html);
        }
        if (response["pestcontrol"]) {
          let items = response["pestcontrol"];
          let html =
            "<table class='table table-striped'><tr><th>Pesticide Date</th><th>Name</th></th><th>Quantity</th></tr>";
          items.forEach((item) => {
            let dt = item.date.replace("T", ", ");
            dt = dt.split(".")[0];
            html +=
              "<tr><td>" +
              dt +
              "</td><td>" +
              item.name +
              "</td><td>" +
              item.quantity +
              "</td></tr>";
          });
          html += "</table>";
          $("#pestcontrol").html(html);
        }
        if (response["harvestcrop"]) {
          let harvest = response["harvestcrop"];
          let html =
            "<table class='table table-striped'><tr><th>Harvest Date</th><th>Crop</th><th>Quantity</th></tr>";
          harvest.forEach((item) => {
            let dt = item.date.replace("T", ", ");
            dt = dt.split(".")[0];
            html +=
              "<tr><td>" +
              dt +
              "</td><td>" +
              item.crop +
              "</td><td>" +
              item.quantity +
              "</td></tr>";
          });
          html += "</table>";
          $("#harvest").html(html);
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.log("Error:", textStatus, errorThrown);
      },
    });
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
          if (isInsideGeofence(userLocation)) {
            // this part runs if user is in the geofence area
            // console.log("inside");
          } else {
            // console.log("last");
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

  $(document).ready(function () {
    setTimeout(() => {
      getAllFields();
    }, 1000);
  });

  // track field on move
  let trackEnabled = false;
  let intervalId = null; // Store the ID value returned by setInterval

  trackField = () => {
    if (!trackEnabled) {
      $("#loading").show();
      $("#buttonHolder").hide();
      $("#fieldTrackingButton").text("Stop Live Tracking");
      $("#fieldTrackingButton")
        .addClass("btn-danger")
        .removeClass("text-light");
      checkStatus();
      intervalId = setInterval(() => {
        checkStatus();
      }, 5000);
      trackEnabled = true;
    } else {
      clearInterval(intervalId);
      $("#loading").hide();
      $("#buttonHolder").show();
      $("#fieldTrackingButton").text("Enable Live Field Tracking");
      $("#fieldTrackingButton")
        .addClass("text-light")
        .removeClass("btn-danger");
      trackEnabled = false;
    }
  };
}
