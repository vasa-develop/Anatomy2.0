﻿/**
 * Copyright 2015 Adam Brunnmeier, Dominik Studer, Alexandra Wörner, Frederik Zwilling, Ali Demiralp, Dev Sharma, Luca Liehner, Marco Dung, Georgios Toubekis
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @file login.js
 * Contains all client side logic for login functionality
 */

/**
 * Handler function for clicking the "Submit" button on login.php
 * Does an ajax post request to server (where login credentials are checked)
 */
 /*
function onClickLogin() {
  var email = document.getElementById('login_email').value;
  var password = document.getElementById('login_password').value;
  // If email or password are empty, there is no need to check credentials on server
  if (email && password) {
    // Show a little animated "loading" image
    document.getElementById('login_loader').style.display = "inline";
    // Send an ajax post request using helper functions in ajax.js
    ajax.post("../php/check_credentials.php", {login_email:email, login_password:password}, function(data) {
      // The data we receive from server is JSON encoded, so we have to decode first
      data = JSON.parse(data);
      if (data.result == 'ok') {
        // Reload page to refresh all data
        window.location.reload(false);
        // Propagate refresh to nearby pages if in role.
        publishIWC("Reload", new Object());
      }
      else {
        document.getElementById('login_status').innerHTML = data.result;
      }
      // Hide the "loading" animation
      document.getElementById('login_loader').style.display = "none";
    });
  }
}
*/

function initLogin() {
  btn = document.getElementById('btn_request_lecturer');
  if(btn !== null) {
    btn.addEventListener('click', onRequestLecturerClick, false);
  }
}

/**
 * Event handler for register (=create account) button in register_as_tutor.php
 * Sends ajax request to server to create account.
 */
function onRequestLecturerClick() {
  btn = document.getElementById('btn_request_lecturer');

  // feedback button pressed  
  btn.style.opacity = .5; //For real browsers;
  btn.style.filter = "alpha(opacity=50)"; //For IE;
  
    ajax.post("../php/register_as_tutor.php", {}, function(msg) {
    	console.log(msg);
      msg = JSON.parse(msg);      
      if (msg.result === true) {

			   div = document.getElementById("div_lecturer_registration");
			  div.innerHTML = `A 3DModels administrator of your university <b>has been contacted</b>. The administrator will
	        enable your account. Afterwards, you can login with lecturer rights.
	        <br> <br>
	        <b>Why is a confirmation needed?</b> <br>
	        Your account enables functionality that is restricted to be used by lecturers only. The administrator therefore makes sure, that
	        only lecturers have an account.`;
      }
      else {
		  btn.style.opacity = 1; //For real browsers;
		  btn.style.filter = "alpha(opacity=100)"; //For IE;
      }
    });
  }

/// Call initialize for registration when DOM loaded
document.addEventListener('DOMContentLoaded', initLogin, false);

