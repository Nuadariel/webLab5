<?php
	session_start();
?>

<html>
<head>
	<!-- <script type="text/javascript" src="jquery-3.3.1.min.js"></script> -->
	<script src="https://code.jquery.com/jquery-1.12.4.min.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js" type="text/javascript"></script>
	<script type="text/javascript">
   	$(document).ready(function(){
        // =validation
        //alert("ok");
        $("#data").validate({
            submitHandler: function(form){
                //alert("ok1");
                var form = document.forms[0],
                    formData = new FormData(form),
                    xhr = new XMLHttpRequest();
                //alert("ok2");
                formData.append('model','user');
                formData.append('action','register');
                console.log(document.forms);
                // xhr.open("POST", "PHP/register.php");
                xhr.open("POST", "Route.php");
                xhr.responseType = 'text';
                 xhr.onreadystatechange = function() {
                     if (xhr.readyState == 4) {
                         if(xhr.status == 200) {
                             // alert("ok3");
                            if (xhr.responseText)
                            	 alert(xhr.responseText);
                         	else
                         		location.href='index.php';
                         }
                     }
                 };
                xhr.send(formData);
            }
        });
    })
</script>
	<title></title>
</head>
<body>
	<p>
		<left>
			<form id="data" method="post" enctype="multipart/form-data">
				<p>
					login
					<input type="text" name="login" required>
				</p>
				<p>
					password
					<input type="password" name="password1" required>
				</p>
				<p>
					repeat password
					<input type="password" name="password2" required>
				</p>
				<p>
					first name
					<input type="text" name="fname" required>
				</p>
				<p>
					last name
					<input type="text" name="lname" required>
				</p>
				<?php
					if (isset($_SESSION['role']) && !strcasecmp($_SESSION['role'], "admin")) {
						echo "<p>role
                              	<select name='role'  value='admin'>
                                    <option value='admin'>admin</option>
                                    <option value='user'>user</option>
                              	</select>
                              	</p>";
					}
				?>
				<p>
					
					<input type="submit" id="register" value="Регистрация" name="register">
					<input type="button" name="back" value="Выйти" onclick="location.href='index.php' ">
				</p>
			</form>
		</left>
		<div id="results"></div>
	</p>
</body>