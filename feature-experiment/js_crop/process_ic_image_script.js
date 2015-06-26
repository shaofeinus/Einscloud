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

function fillInForms(results, side) {

    if(side === "front") {
        var nric = document.createElement("p");
        nric.innerHTML = "NRIC: " + results.nric;
        var name = document.createElement("p");
        name.innerHTML = "Name: " + results.name;
        var race = document.createElement("p");
        race.innerHTML = "Race: " + results.race;
        var dob = document.createElement("p");
        dob.innerHTML = "Date of birth: " + results.dob;
        var gender = document.createElement("p");
        gender.innerHTML = "Gender: " + results.gender;

        var frontFields = document.getElementById("front_fields");
        frontFields.innerHTML = "";
        frontFields.appendChild(nric);
        frontFields.appendChild(name);
        frontFields.appendChild(race);
        frontFields.appendChild(dob);
        frontFields.appendChild(gender);
    }

    if(side === "back") {
        var address = document.createElement("p");
        address.innerHTML = "Address: " + results.address;
        var backFields = document.getElementById("back_fields");
        backFields.innerHTML = "";
        backFields.appendChild(address);
    }
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