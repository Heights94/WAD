/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function validateLogin() {
    var username = $("#userName").val();
    var password = $("#password").val();//check for blank visible and use as a condition
    if (username === '' || password === '') {
        if (username === '') {
            $("#validate").text("*Please fill in the username field");
        } else if (password === '') {
            $("#validate").text("*Please fill in the password field");
        }
//        sweetAlert("Oops...", "Please fill in both your Username and Password!", "info");
//        document.getElementById("Login").disabled = false;
        return false;
    } else {

        $("#validate").text("");
//        $.ajax({
//            type: "post",
//            url: "mysql.php",
//            data: "login=Login" + "&userName=" + username + "&password=" + password,
//            success: function (data) {
//                location.reload();
//                return false;
//            }
//        });
        console.log('This is here...');
        return true;
    }
}

function validateRegister() {
    var username = $("#userName").val();
    var password = $("#password").val();
    var email = $("#email").val();
    var captcha = $("#captcha").val();
    if (username === '' || password === '' || email === '' || captcha === '') {
        if (username === '') {
            $("#validate").text("*Please fill in the username field");
        } else if (password === '') {
            $("#validate").text("*Please fill in the password field");
        } else if (email === '') {
            $("#validate").text("*Please fill in the email field");
        } else if (captcha === '') {
            $("#validate").text("*Please fill in the captcha field");
        } else {
            $("#validate").text("");
        }
//        sweetAlert("Oops...", "Please fill in all fields!", "info");
//        document.getElementById("Login").disabled = false;
        return false;

    } else {
        if (!validateEmail(email)) {
            $("#validate").text("*Email is not in the appropiate format!");
            return false;
//        sweetAlert("Oops...", "Email is not in the appropiate format!", "info");
        }
        $("#validate").text("");
//        $.ajax({
//            type: "post",
//            url: "mysql.php",
//            data: "register=Register" + "&userName=" + username + "&password=" + password + "&email=" + email + "&captcha=" + captcha,
//            success: function (data) {
//                window.location = 'verification.php';
//                return false;
//            }
//        });
        return true;
    }
}

function validateEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}