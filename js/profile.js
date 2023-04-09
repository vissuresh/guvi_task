if (localStorage.getItem("token") == null) {
    location.href = "login.html";
}

else{
    function getUserData() {
        let userData = localStorage.getItem("userData");
        let data = JSON.parse(userData);
        let cur_user = {
            email: data.email,
        }

        $.ajax({
            method: "POST",
            url: "php/getMongoData.php",
            data: JSON.stringify(cur_user),
            contentType : 'application/json',
            cache: false,

            success: function (response) {
                var userData = JSON.parse(response);
                $("#cur_first_name").html(userData.first_name);
                $("#cur_last_name").html(userData.last_name);
                $("#cur_dob").html(userData.dob);
                $("#cur_phone").html(userData.phone);
                $("#cur_address").html(userData.address);
                
                
                // Populating the form
                $("#first_name").html(userData.first_name);
                $("#last_name").html(userData.last_name);
                $("#dob").html(userData.dob);
                $("#phone").html(userData.phone);
                $("#caddress").html(userData.address);

            },

            error: function (xhr, status, error) {
                console.log("Error Response: ", xhr.responseText);
                console.log(error);
            }
        });

    }

    getUserData();

    $(document).ready(function () {
        $('#submit').on('click', function (e){

            e.preventDefault();

            let userData = localStorage.getItem("userData");
            let data = JSON.parse(userData);
            let email = data.email;

            let first_name, last_name, dob, phone, address;
            first_name = $('#first_name').val();
            last_name = $('#last_name').val();
            dob = $('#dob').val();
            address = $('#address').val();

            if (first_name == "" || last_name == "" || dob == "" || phone == "" || address == "") {
                alert("Incomplete Form Data");
            }

            else{
                let newData = {
                    first_name: first_name,
                    last_name: last_name,
                    dob: dob,
                    phone: phone,
                    address: address,
                }

                $.ajax({
                    method: 'POST',
                    url: 'php/editMongoDB.php',
                    data: JSON.stringify(newData),
                    contentType: 'application/json',

                    
                    success: function (response) {
                        console.log(response)
                        
                        if(response=='UPDATE_SUCCESS'){
                            alert("Data updated successfully");
                        }

                        else{
                            alert('Error');
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
}
