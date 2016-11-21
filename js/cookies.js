function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function checkCookie(username) {
    var user = getCookie("username");//Checks if a cookie is already set
    if (user != "") {//Do something if there is a cookie
        alert("Welcome again " + user);
    } else {//Else, make a cookie for the first time.
//        console.log(username);
        setCookie("username", username, 365);
    }
}

function deleteCookie(username) {
    var user = getCookie("username");//Retrieve the current username cookie (can be empty).
    if (username !== user && user !== '') {//If a new user has logged in successfully and there is a cookie...
        setCookie("username", username, -1);//...Delete the cookie to make a new login username. 
    }
}

function ajax_login2() {
    var username = $("#userName").val();//The value from the environment dropdown will determine which table in MySQL to look in. 
    var password = $("#password").val();
    $.ajax({
        type: "post",
        url: "mysql.php",
        data: "action=login" + "&userName=" + username + "&password=" + password,
        success: function (data) {
////            if (data) {
//                   console.log(data);
//                alert("Matches found");
//                deleteCookie(username);//New user logged in, delete last username cookie if there was one
//                checkCookie(username);//Makes a new cookie if there isn't one already
                return false;
//            } else if (!data) {//If there were no SQL matches, do not proceed..
//                console.log(data);
//                alert("No login matches could be found");
//            }
        }
    });
    return false;
}