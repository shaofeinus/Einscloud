/**
 * Created by Shao Fei on 26/6/2015.
 */

COMPRESS_RATIO = 0.7;

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

    var formField = document.createElement("DIV");

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
        formField.appendChild(nric);
        formField.appendChild(name);
        formField.appendChild(race);
        formField.appendChild(dob);
        formField.appendChild(gender);
    }

    if(side === "back") {
        var address = document.createElement("p");
        address.innerHTML = "Address: " + results.address;
        formField.appendChild(address);
    }

    document.body.appendChild(formField);
}

function compressImage(img) {
    var canvasFullIC = document.createElement("CANVAS");
    canvasFullIC.setAttribute("width", img.width * COMPRESS_RATIO);
    canvasFullIC.setAttribute("height", img.height * COMPRESS_RATIO);
    var contextFullIC = canvasFullIC.getContext("2d");

    contextFullIC.drawImage(img, 0, 0, img.width * COMPRESS_RATIO, img.height * COMPRESS_RATIO);

    var compressedImage = new Image();
    compressedImage.src = canvasFullIC.toDataURL("image/jpeg");
    return compressedImage;
}