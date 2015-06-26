/**
 * Created by Shao Fei on 24/6/2015.
 */

// Numbers for specs are in ratio form
IC_SPECS = {width:9.8, height:6.2};
NRIC_SPECS = {width:3.0, height:0.6, xOffset:3.6, yOffset:0.7};
NAME_SPECS = {width:6.0, height:0.5, xOffset:3.4, yOffset:2.3};
RACE_SPECS = {width:4.0, height:0.4, xOffset:3.4, yOffset:3.8};
DOB_SPECS = {width:2.0, height:0.4, xOffset:3.4, yOffset:4.7};
GENDER_SPECS = {width:0.5, height:0.4, xOffset:5.4, yOffset:4.7};
ADDRESS_SPECS = {width:7.0, height:1.2, xOffset:0.7, yOffset:4.9};
FULL_IC_CROP_RATIO = 325;

// Numbers for specs are in px
DESIRED_IC_DIMENSION = {width:980, height:620};

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

            // Obtain a image that is fully filled up by IC
            var fullICImage = drawFullICImage(img);

            if(side === "front") {
                cropAndSave("nric", fullICImage);
                cropAndSave("name", fullICImage);
                cropAndSave("race", fullICImage);
                cropAndSave("dob", fullICImage);
                cropAndSave("gender", fullICImage);
            }

            if(side === "back") {
                cropAndSave("address", fullICImage);
            }
        }
    )
}

function drawFullICImage(img) {
    var canvasFullIC = document.createElement("CANVAS");
    canvasFullIC.setAttribute("width", IC_SPECS.width * FULL_IC_CROP_RATIO);
    canvasFullIC.setAttribute("height", IC_SPECS.height * FULL_IC_CROP_RATIO);
    var contextFullIC = canvasFullIC.getContext("2d");

    contextFullIC.drawImage(img, 0, 0,
        IC_SPECS.width * FULL_IC_CROP_RATIO,
        IC_SPECS.height * FULL_IC_CROP_RATIO, 0, 0,
        IC_SPECS.width * FULL_IC_CROP_RATIO,
        IC_SPECS.height * FULL_IC_CROP_RATIO);

    var fullICImage = new Image();
    fullICImage.src = canvasFullIC.toDataURL();
    document.body.appendChild(fullICImage);
    return fullICImage;
}

function cropAndSave(section, fullICImage) {

    var cropSpecs = determineSpecs(section);

    var canvas = document.createElement("CANVAS");
    canvas.setAttribute("width", cropSpecs.IMAGE_WIDTH);
    canvas.setAttribute("height", cropSpecs.IMAGE_HEIGHT);
    var context = canvas.getContext('2d');

    context.drawImage(fullICImage,
        cropSpecs.START_CROP_X,
        cropSpecs.START_CROP_Y,
        cropSpecs.CROP_WIDTH,
        cropSpecs.CROP_HEIGHT,
        cropSpecs.POSITION_X,
        cropSpecs.POSITION_Y,
        cropSpecs.IMAGE_WIDTH,
        cropSpecs.IMAGE_HEIGHT);

    postImage(canvas, section);

    /* Obtain orientation of image */
    /*
     loadImage.parseMetaData (
         document.getElementById("input_image").files[0],
         function (data) {
             var orientation = data.exif.get("Orientation");

             var context = canvas.getContext('2d');
             context.translate(250, 250);

             if(orientation === 6) {
                 context.rotate(Math.PI/2);
             }

             context.translate(-250, -250);

             //context.drawImage(img, 0, 0, 100, 100);
             //context.drawImage(img, 0, 0);
             context.drawImage(img, START_CROP_X,START_CROP_Y,CROP_WIDTH,CROP_HEIGHT,POSITION_X,POSITION_Y,IMAGE_WIDTH,IMAGE_HEIGHT);

             postImage(canvas);
         }
     );
     */
}

function determineSpecs(section) {

    var imgWidth = IC_SPECS.width * FULL_IC_CROP_RATIO;
    var imgHeight = IC_SPECS.height * FULL_IC_CROP_RATIO;

    var cropSpecs;

    switch (section) {
        case "nric":
            cropSpecs = {
                START_CROP_X: NRIC_SPECS.xOffset / IC_SPECS.width * imgWidth,
                START_CROP_Y: NRIC_SPECS.yOffset / IC_SPECS.height * imgHeight,
                CROP_WIDTH: NRIC_SPECS.width / IC_SPECS.width * imgWidth ,
                CROP_HEIGHT: NRIC_SPECS.height / IC_SPECS.height * imgHeight,
                POSITION_X:0,
                POSITION_Y:0,
                IMAGE_WIDTH: NRIC_SPECS.width / IC_SPECS.width * DESIRED_IC_DIMENSION.width ,
                IMAGE_HEIGHT: NRIC_SPECS.height / IC_SPECS.height * DESIRED_IC_DIMENSION.height};
            break;
        case "name":
            cropSpecs = {
                START_CROP_X: NAME_SPECS.xOffset / IC_SPECS.width * imgWidth,
                START_CROP_Y: NAME_SPECS.yOffset / IC_SPECS.height * imgHeight,
                CROP_WIDTH: NAME_SPECS.width / IC_SPECS.width * imgWidth ,
                CROP_HEIGHT: NAME_SPECS.height / IC_SPECS.height * imgHeight,
                POSITION_X:0,
                POSITION_Y:0,
                IMAGE_WIDTH: NAME_SPECS.width / IC_SPECS.width * DESIRED_IC_DIMENSION.width ,
                IMAGE_HEIGHT: NAME_SPECS.height / IC_SPECS.height * DESIRED_IC_DIMENSION.height};
            break;
        case "race":
            cropSpecs = {
                START_CROP_X: RACE_SPECS.xOffset / IC_SPECS.width * imgWidth,
                START_CROP_Y: RACE_SPECS.yOffset / IC_SPECS.height * imgHeight,
                CROP_WIDTH: RACE_SPECS.width / IC_SPECS.width * imgWidth ,
                CROP_HEIGHT: RACE_SPECS.height / IC_SPECS.height * imgHeight,
                POSITION_X:0,
                POSITION_Y:0,
                IMAGE_WIDTH: RACE_SPECS.width / IC_SPECS.width * DESIRED_IC_DIMENSION.width ,
                IMAGE_HEIGHT: RACE_SPECS.height / IC_SPECS.height * DESIRED_IC_DIMENSION.height};
            break;
        case "dob":
            cropSpecs = {
                START_CROP_X: DOB_SPECS.xOffset / IC_SPECS.width * imgWidth,
                START_CROP_Y: DOB_SPECS.yOffset / IC_SPECS.height * imgHeight,
                CROP_WIDTH: DOB_SPECS.width / IC_SPECS.width * imgWidth ,
                CROP_HEIGHT: DOB_SPECS.height / IC_SPECS.height * imgHeight,
                POSITION_X:0,
                POSITION_Y:0,
                IMAGE_WIDTH: DOB_SPECS.width / IC_SPECS.width * DESIRED_IC_DIMENSION.width ,
                IMAGE_HEIGHT: DOB_SPECS.height / IC_SPECS.height * DESIRED_IC_DIMENSION.height};
            break;
        case "gender":
            cropSpecs = {
                START_CROP_X: GENDER_SPECS.xOffset / IC_SPECS.width * imgWidth,
                START_CROP_Y: GENDER_SPECS.yOffset / IC_SPECS.height * imgHeight,
                CROP_WIDTH: GENDER_SPECS.width / IC_SPECS.width * imgWidth ,
                CROP_HEIGHT: GENDER_SPECS.height / IC_SPECS.height * imgHeight,
                POSITION_X:0,
                POSITION_Y:0,
                IMAGE_WIDTH: GENDER_SPECS.width / IC_SPECS.width * DESIRED_IC_DIMENSION.width ,
                IMAGE_HEIGHT: GENDER_SPECS.height / IC_SPECS.height * DESIRED_IC_DIMENSION.height};
            break;
        case "address":
            cropSpecs = {
                START_CROP_X: ADDRESS_SPECS.xOffset / IC_SPECS.width * imgWidth,
                START_CROP_Y: ADDRESS_SPECS.yOffset / IC_SPECS.height * imgHeight,
                CROP_WIDTH: ADDRESS_SPECS.width / IC_SPECS.width * imgWidth ,
                CROP_HEIGHT: ADDRESS_SPECS.height / IC_SPECS.height * imgHeight,
                POSITION_X:0,
                POSITION_Y:0,
                IMAGE_WIDTH: ADDRESS_SPECS.width / IC_SPECS.width * DESIRED_IC_DIMENSION.width ,
                IMAGE_HEIGHT: ADDRESS_SPECS.height / IC_SPECS.height * DESIRED_IC_DIMENSION.height};
            break;
        default:
            cropSpecs = null;
            break;
    }

    return cropSpecs;
}

function postImage(canvas, section) {

    var imgData = canvas.toDataURL("image/jpeg", 0.9);

    $.post('save_cropped_img.php',
        {
            img: imgData,
            fileName: section
        },
        function(data, status) {
            console.log(data);
        });
}