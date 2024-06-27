verifyToken = () => {
  const access_token = localStorage.getItem("access_token");
  $.ajax({
    url: base_url + "api/token/verify/",
    type: "POST",
    data: JSON.stringify({
      token: access_token,
    }),
    contentType: "application/json",
    success: function (response, statusText, xhr) {
      if (xhr.status === 200) {
        sessionStorage.setItem("user_logged", true);
      }
    },
  }).fail(function (response) {
    updateAccessToken();
  });
};

updateAccessToken = () => {
  const refresh_token = localStorage.getItem("refresh_token");
  $.ajax({
    url: base_url + "api/token/refresh/",
    type: "POST",
    headers: {
      "Content-Type": "application/json",
      Authorization: "Bearer " + localStorage.getItem("access_token"),
    },
    data: JSON.stringify({
      refresh: refresh_token,
    }),
    success: function (response, statusText, xhr) {
      console.log(response);
      if (response.access) {
        localStorage.setItem("access_token", response.access);
        window.location.reload();
      }
    },
  }).fail(function (response) {
    window.location.href = "/pages/login.php";
  });
};

let base_url = "http://127.0.0.1:8000/";
verifyToken();
