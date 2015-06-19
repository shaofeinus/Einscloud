/**
 * Created by CK on 15/6/2015.
 */


function emailViewer(email, firstName, lastName){
    $.post('../../email_viewer/caregive_email.php',
    {
        email: email,
        firstName: firstName,
        lastName: lastName

    });
    console.log(firstName);
}
