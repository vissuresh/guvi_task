function formValidation(email, dob, password, conf_password){
    let emailRegex = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    let PasswordRegex = /^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*\W)(?!.* ).{8,16}$/;
    const today = new Date();
    let dob_date = new Date(dob);

    if(emailRegex.test(email) == false){
        $('#email').attr('class', 'form-control is-invalid');
        return false;
    }
    else {
        $("#email").attr("class", "form-control");
    }

    if(dob>= today){
        $('#dob').attr('class', 'form-control is-invalid');
        return false;
    }
    else {
        $("#dob").attr("class", "form-control");
    }



    if(PasswordRegex.test(password) == false){
        $('#password').attr('class', 'form-control is-invalid');
        return false;
    }
    else {
        $("#password").attr("class", "form-control");
    }

    if(password != conf_password){
        $('#conf_password').attr('class', 'form-control is-invalid');
        return false;
    }
    else{
        $('#conf_password').attr('class', 'form-control');
    }

    return true;
}

$(document).ready(function() {

    $('#register').on('click', function (e) {
        e.preventDefault();

        let first_name, last_name, email, dob, password, conf_password;

        first_name = $('#first_name').val();
        last_name = $('#last_name').val();
        email = $('#email').val();
        dob = $('#dob').val();
        password = $('#password').val();
        conf_password = $('#conf_password').val();
    
        if(first_name=='' || last_name == '' || email == '' || dob == '' || password=='' || conf_password==''){
            alert('Incomplete Form Data');
            return;
        }
    
        if(formValidation(email=email, dob=dob, password=password, conf_password=conf_password) == true){
            
            let registerInfo = {
                first_name : first_name,
                last_name : last_name,
                dob : dob,
                email : email,
                password : password,
            }

            $.ajax({
                method: 'POST',

                url: "php/register.php",

                data: JSON.stringify(registerInfo),

                contentType: "application/json",

                cache: false,


                success: function (response) {
                    if (response === "REGISTRATION_SUCCESS") {
                        alert("Registration Success")
                        location.href = "login.html"
                    }
                    else {
                        console.log("Error: ", response);
                        alert("Error");
                    }
                },

                error: function (xhr, status, error) {
                    console.log("Error Response: ", xhr.responseText);
                    console.log(error);
                }
            });

        }
    
    });
});