/**
 * Created by Shao Fei on 24/6/2015.
 */
var START_CROP_X = 500;
var START_CROP_Y = 500;
var CROP_WIDTH = 800;
var CROP_HEIGHT = 800;
var POSITION_X = 0;
var POSITION_Y = 0;
var IMAGE_WIDTH = 800;
var IMAGE_HEIGHT = 800;
var CANVAS_WIDTH = 800;
var CANVAS_HEIGHT = 800;

function cropAndSave() {

    // Loads image from input and returns a <img> element
    loadImage(
        document.getElementById("input_image").files[0],
        function (img) {
            process(img);

        }
    )
}

function drawImage(canvas, img) {
    /* Obtain orientation of image */
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
}

function process(img) {

    var canvas = document.createElement("CANVAS");
    canvas.setAttribute("id", "crop_canvas");
    canvas.setAttribute("width", CANVAS_WIDTH);
    canvas.setAttribute("height", CANVAS_HEIGHT);

    drawImage(canvas, img);
}

function postImage(canvas) {

    var imgData = canvas.toDataURL("image/jpeg", 0.1);

    $.post('save_cropped_img.php',
        {
            img: imgData
        },
        function(data, status) {
            console.log(data);
        });
}