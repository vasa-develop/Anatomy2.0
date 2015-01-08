/**
 * @file menuToolbar.js
 * Provides event handler for click events of all toolbar buttons
 * Also initializes toolbar elements if needed
 */

/**
 * Initialize the combo box for navigation modes in toolbar
 */
function initNavigationMode() {
  document.getElementById('optionExamine').setAttribute("selected", "selected");
}
/// Call initialize for navigation mode when DOM loaded
document.addEventListener('DOMContentLoaded', initNavigationMode, false);

/**
 * Button "Show All" functionality
 */
function showAll() {
  document.getElementById('viewer_object').runtime.showAll();
}

/**
 * Updates navigation mode in X3Dom to match the one selected in combo box in toolbar
 */
function x3dChangeView() {
  var select = document.getElementById('viewModeSelect');
  document.getElementById('navType').setAttribute("type", select.options[select.selectedIndex].value);
}

/**
 * Initializes the copy to clipboard functionality
 */
function initCopy() {
  // ZeroClipboard needs to know where it can find the ".swf" file (flash movie)
  ZeroClipboard.config( { swfPath: 'http://eiche.informatik.rwth-aachen.de/henm1415g2/src/swf/ZeroClipboard.swf' } );
  // Create client instance and attach copy event to it
  var client = new ZeroClipboard();
  client.on( "copy", function (event) {
    var clipboard = event.clipboardData;
    clipboard.setData( "text/plain", window.location.href );
  });
  // Glue button to client. The copy event is fired when button is clicked
  client.clip( document.getElementById("btnCopy") );
}
document.addEventListener("DOMContentLoaded", initCopy, false);

/**
 * Stops or starts synchronization with the other viewer widget(s) respectively
 * Enables/disables synchronization of the widget with others
 */
function x3dSynchronize() {
  var btn = document.getElementById('btnSynchronize');
  if (isSynchronized) {
    btn.innerHTML ="Synchronize";
    savePositionAndOrientation();
  }
  else {
    btn.innerHTML ="Unsynchronize";
    synchronizePositionAndOrientation();
  }
  isSynchronized = !isSynchronized;
}

/**
 * Attached to "btnInfo" (Show info / Hide info)
 * Will turn the statistics view on and off based on current status
 * Will turn the metadata_overlay on and off accordingly
 */
function showInfo() {
  var x3dom = document.getElementById('viewer_object');
  var btn = document.getElementById('btnInfo');
  var metadata_overlay = document.getElementById('metadata_overlay');
  if (btn.innerHTML === "Show info") {
    x3dom.runtime.statistics(true);
    btn.innerHTML = "Hide info";
    metadata_overlay.style.display = "block";
  }
  else {
    x3dom.runtime.statistics(false);
    btn.innerHTML = "Show info";
    metadata_overlay.style.display = "none";
  }
}