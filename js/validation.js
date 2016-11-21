/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function validate_login() {
    var username = $("#userName").val();
    var password = $("#password").val();
    if (username === '' || password === '') {
        sweetAlert("Oops...", "Please fill in both your Username and Password!", "info");
//        document.getElementById("Login").disabled = false;
        return false;
    } else {
        $.ajax({
            type: "post",
            url: "mysql.php",
            data: "login=Login" + "&userName=" + username + "&password=" + password,
            success: function (data) {
                location.reload();
                return false;
            }
        });
        return false;
    }
}

