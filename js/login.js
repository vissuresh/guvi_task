$(document).ready(function () {
    $("#login").on('click', function (e) {
        e.preventDefault();

        let email, password;
        email = $("#email").val();
        password = $("#password").val();
        
        if(email == '' || password ==''){
            alert('Incomplete Form Data');
            return;
        }


        else{
            let loginInfo = {
                email: email,
                password: password,
            }
            
            $.ajax({
                method: "POST",
    
                url: "php/login.php",
    
                data: JSON.stringify(loginInfo),
    
                cache: false,
    
                contentType: "application/json",
    
                success: function (response) {
                    if (response === "USER_NOT_FOUND") {
                        alert("Username does not exist!");
                    }
    
                    else{
                        console.log(response);
                        alert(response);
                    }
    
                    // else {
                    //     console.log(JSON.parse(response));
                    //     localStorage.setItem("userData", response);
                    //     localStorage.setItem("token", loginInfo);
                    //     location.href = "profile.html";
                    // }
                },
    
                error: function (xhr, status, error) {
                    console.log("Error Response: ", xhr.responseText);
                    console.log(error);
                }
            });

        }

    });
});