/**
 * Created by Shao Fei on 1/7/2015.
 */

function displayOthersInput() {
    validateRace();
    if(document.getElementById("race").value === "otherRaces") {
        document.getElementById("otherRaceInput").setAttribute("style", "display:inline");
    } else {
        document.getElementById("otherRaceInput").setAttribute("style", "display:none");
    }
}

function showImageCaptureMenu() {
    $('#disclaimer').attr('style','display:none;');
    $('#agreementButtons').attr('style', 'display:none;');
    $('#denyImageCapture').attr('style', 'display:none;');
    $('#imageCaptureButtons').attr('style', 'display:inline;');
}

function showImageCaptureDenied() {
    $('#denyImageCapture').attr('style', 'display:inline;');
}

function defaultUserInfo() {
    document.getElementById('username').readOnly = true;
    document.getElementById('password').readOnly = true;
    document.getElementById('confirm_password').readOnly = true;
    document.getElementById('username').style.backgroundColor = '#dddddd';
    document.getElementById('password').style.backgroundColor = '#dddddd';
    document.getElementById('confirm_password').style.backgroundColor = '#dddddd';
    document.getElementById('username').value = '';
    document.getElementById('password').value = '';
    document.getElementById('confirm_password').value = '';

    document.getElementById('username_feedback').innerHTML = "Auto generated";
    document.getElementById('password_feedback').innerHTML = "Auto generated - first 6 digit of your phone number";

    // Functions from default username script
    generateDefaultUserName();
    generateDefaultPassword();
}

function customUserInfo() {
    document.getElementById('username').readOnly = false;
    document.getElementById('password').readOnly = false;
    document.getElementById('confirm_password').readOnly = false;
    document.getElementById('username').style.backgroundColor = '#ffffff';
    document.getElementById('password').style.backgroundColor = '#ffffff';
    document.getElementById('confirm_password').style.backgroundColor = '#ffffff';

    document.getElementById('username_feedback').innerHTML = "";
    document.getElementById('password_feedback').innerHTML = "";
}