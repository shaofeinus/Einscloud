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

            $.post('../feature-experiment/js_crop/process_ic_image.php',
                {
                    side: side,
                    img: compressedImage.src
                },
                function(data, status) {
                    var results = JSON.parse(data);
                    console.log(data);
                    fillInForms(results, side);
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

        var fullNameField = document.forms["user_registration_form"]["fullName"];
        fullNameField.value = results.name.replace(/\n/g, "");

        var nricField = document.forms["user_registration_form"]["nric"];
        nricField.value = results.nric.replace(/\n/g, "");;

        var dobField = document.forms["user_registration_form"]["birthday"];
        dobField.value = convertDateString(results.dob.replace(/\n/g, ""));

        fillGender(results.gender.replace(/\n/g, ""));

        fillRace(results.race.replace(/\n/g, ""));
    }

    if(side === "back") {
        var addressField = document.forms["user_registration_form"]["address"];
        addressField.value = results.address.replace(/\n/g, " ");
    }

    // Function from default username script
    generateDefaultUserName();

    // Function from validation script
    validateForm();
}

function convertDateString(dateStringDDMMYYYY) {
    var dateData = dateStringDDMMYYYY.split("-");
    var day = parseInt(dateData[0].replace("0", ""))+1;
    var month = parseInt(dateData[1].replace("0", ""))-1;
    var year = parseInt(dateData[2]);
    var convertedDate = new Date(year, month, day, 0, 0, 0);
    return convertedDate.toISOString().substring(0, 10);
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
    console.log(race);
    if(race === "CHINESE") {
        raceSelect.selectedIndex = 0;
    } else if(race === "MALAY") {
        raceSelect.selectedIndex = 1;
    } else if(race === "INDIAN") {
        raceSelect.selectedIndex = 2;
    } else {
        raceSelect.selectedIndex = 3;
        displayOthersInput();
        document.getElementById("otherRaceInput").value = race;
    }
}