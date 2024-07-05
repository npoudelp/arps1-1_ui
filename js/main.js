showError = (message) => {
  let alertDisplay = $("#alert_diaplay");
  alertDisplay.text(message);
  alertDisplay.parent().prependTo('body');
  alertDisplay.parent().show();
};