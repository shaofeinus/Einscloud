/**
 * Created by Shao Fei on 24/6/2015.
 */
var START_CROP_X = 500;
var START_CROP_Y = 500;
var CROP_WIDTH = 800;
var CROP_HEIGHT = 800;
var POSITION_X = 200;
var POSITION_Y = 200;
var WIDTH = 800;
var HEIGHT = 800;

function cropAndSave() {

    /* Obtain orientation of image */
    loadImage.parseMetaData (
        document.getElementById("input_image").files[0],
        function (data) {
            var orientation = data.exif.get("Orientation");
        }
    )

    // Loads image from input and returns a <img> element
    loadImage(
        document.getElementById("input_image").files[0],
        function (img) {
            var croppedImgData = crop(img);
            postImage(croppedImgData);
        }
    )


}
function crop(img) {

    var canvas = document.createElement("CANVAS");
    canvas.setAttribute("width", 1000);
    canvas.setAttribute("height", 1000);
    var context = canvas.getContext('2d');

    //context.drawImage(img, 0, 0, 100, 100);
    //context.drawImage(img, 0, 0);
    context.drawImage(img, START_CROP_X,START_CROP_Y,CROP_WIDTH,CROP_HEIGHT,POSITION_X,POSITION_Y,WIDTH,HEIGHT);
    //document.getElementById("img_aft").src = canvas.toDataURL("image/png");
    return canvas.toDataURL("image/png");
}

function postImage(imgData) {

    $.post('save_cropped_img.php',
        {
            img: imgData
        },
        function(data, status) {
            console.log(data);
        });
}