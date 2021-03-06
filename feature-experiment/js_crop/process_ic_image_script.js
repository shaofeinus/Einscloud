/**
 * Created by Shao Fei on 26/6/2015.
 */

// Factor to compress image
COMPRESS_FACTOR = 0.7;

function processICImage(side) {

    var fileInput;

    if(side === "front") {
        fileInput = "ic_front";
    } else if (side === "back") {
        fileInput = "ic_back";
    }

    // Load image and returns <img> element
    loadImage(
        document.getElementById(fileInput).files[0],
        function(img) {

            // Compress image to send to server
            var compressedImage = compressImage(img);

            var directory = window.location.pathname.substring(0,  window.location.pathname.lastIndexOf('/'));

            $.post('process_ic_image.php',
                {
                    side: side,
                    img: compressedImage.src
                },
                function(data, status) {
                    var results = JSON.parse(data);
                    fillInForms(results, side);
                    console.log(data);
                });
        }
    )
}

function compressImage(img) {
    var canvasFullIC = document.createElement("CANVAS");
    canvasFullIC.setAttribute("width", img.width * COMPRESS_FACTOR);
    canvasFullIC.setAttribute("height", img.height * COMPRESS_FACTOR);
    var contextFullIC = canvasFullIC.getContext("2d");

    contextFullIC.drawImage(img, 0, 0, img.width * COMPRESS_FACTOR, img.height * COMPRESS_FACTOR);

    var compressedImage = new Image();
    compressedImage.src = canvasFullIC.toDataURL("image/jpeg");
    return compressedImage;
}

function fillInForms(results, side) {

    if(side === "front") {

        var fullNameField = document.forms["user_registration_form"]["fullname"];
        fullNameField.value = results.nric;

        var nricField = document.forms["user_registration_form"]["nric"];
        nricField.value = results.name;

        var dobField = document.forms["user_registration_form"]["birthday"];
        dobField.value = convertDateString(results.dob);

        fillGender(results.gender);

        fillRace(results.race);
    }

    if(side === "back") {
        var addressField = document.forms["user_registration_form"]["address"];
        address.innerHTML = results.address;
    }
}

function convertDateString(dateStringDDMMYYYY) {
    var dateData = dateStringDDMMYYYY.split("-");
    var day = dateData[0];
    var month = dateData[1];
    var year = dateData[2];
    var convertedDate = new Date(year, month, day);
    return convertedDate.toDateString();
}

function fillGender(gender) {
    if(gender === "M") {
        document.getElementById("female").checked = false;
        document.getElementById("male").checked = true;
    } else if(gender === "F") {
        document.getElementById("male").checked = false;
        document.getElementById("female").checked = true;
    }
}

function fillRace(race) {
    var raceSelect = document.getElementById("race");
    if(race === "CHINESE") {
        raceSelect.selectedIndex = 1;
    } else if(race === "MALAY") {
        raceSelect.selectedIndex = 2;
    } else if(race === "INDIAN") {
        raceSelect.selectedIndex = 3;
    } else {
        raceSelect.selectedIndex = 4;
        document.getElementById("otherRaceInput").value = race;
    }
}