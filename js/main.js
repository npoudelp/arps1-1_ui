showError = (message) => {
  $("#alert_diaplay").text(message);
  $("#alert_diaplay").parent().show();
  $("#username").val("");
  $("#password").val("");
};
