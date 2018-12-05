<?php
	session_start();
?>
<html>
<head>
	<script src="https://code.jquery.com/jquery-1.12.4.min.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		//заполнение таблицы
		function createTable() {
			var role='';
			$.ajax({
           type: "POST",
           url: "Route.php",
           data: {
            model:'functional',
            action:'get_user_role'
           },
           success: function(data)
           {
           	role=data;
           	if (role.localeCompare("none")) {
           		document.getElementById('log_in').hidden=true;
                document.getElementById('user_log_in').hidden=false;
                if (role.localeCompare("admin"))
                	document.getElementById('admin_reg').hidden=true;
                else
                	document.getElementById('admin_reg').hidden=false;
           	}else{
           		document.getElementById('log_in').hidden=false;
                document.getElementById('user_log_in').hidden=true;
           	}
           }
         });

			$.ajax({
           type: "POST",
           url: "Route.php",
           data: {
            model:'functional',
            action:'getelementsdb'
           },
           success: function(data)
           {
           	var result = JSON.parse(data),
           		str='';
			document.getElementById('table_users').innerHTML='';
           	result.forEach(function(item, i, arr) {
           		str="<tr><th>"+(i+1)+"</th><th>"+item['login']+"</th><th>"+item['fname']+"</th><th>"+item['lname']+"</th><th>"+item['role']+"</th><th><img width='100' height='70'src='"+item['img']+"'></th><th><button value='"+item['id']+"' onClick='see_profile(this.value)'>Посмотреть</button>";
           		if (!role.localeCompare("admin")) {
           			str+="<p><button value='"+item['id']+"' onClick='edit_profile(this.value)'>Редактировать</button></p><p><button value='"+item['id']+"' onClick='delete_profile(this.value)'>Удалить</button></p>"
           		}
           		str+="</th></tr>";
           		document.getElementById('table_users').innerHTML+=str;
           	})
           }
         });
		}
		window.onload=createTable();

    function sort(argument) {
      var role='';
        $.ajax({
                type: "POST",
                url: "Route.php",
                data: {
                  model:'functional',
                  action:'get_user_role'
                },
                success: function(data)
                {
                  role=data;
                }
            });
      $.ajax({
           type: "POST",
           url: "Route.php",
           data: {
            model:'functional',
            action:'sort'
           },
           success: function(data)
           {
            var result = JSON.parse(data),
              str='';
            document.getElementById('table_users').innerHTML='';
            result.forEach(function(item, i, arr) {
              str="<tr><th>"+(i+1)+"</th><th>"+item['login']+"</th><th>"+item['fname']+"</th><th>"+item['lname']+"</th><th>"+item['role']+"</th><th><img width='100' height='70'src='"+item['img']+"'></th><th><button value='"+item['id']+"' onClick='see_profile(this.value)'>Посмотреть</button>";
              if (!role.localeCompare("admin")) {
                str+="<p><button value='"+item['id']+"' onClick='edit_profile(this.value)'>Редактировать</button></p><p><button value='"+item['id']+"' onClick='delete_profile(this.value)'>Удалить</button></p>"
              }
              str+="</th></tr>";
              document.getElementById('table_users').innerHTML+=str;
            })
           }
         });
    }

		function search(e) {
			if (document.getElementById('search').value!="") {
				var role='';
				$.ajax({
	           		type: "POST",
	           		url: "Route.php",
	           		data: {
                  model:'functional',
                  action:'get_user_role'
	           		},
	           		success: function(data)
	           		{
	           			role=data;
	           		}
	         	});
				$.ajax({
           			type: "POST",
          			url: "Route.php",
           			data: {
                  model:'functional',
                  action:'search',
           				search: document.getElementById('search').value
           			},
           			success: function(data)
           			{
                  var result = JSON.parse(data),
                  str='';

                  if (result.length==0) {
                    alert("Нет пользователей с таким логином");
                    return;
                  }
           				
                  document.getElementById('search_table').innerHTML='<table border="1">';
                  result.forEach(function(item, i, arr) {
                  str="<tr><th>"+(i+1)+"</th><th>"+item['login']+"</th><th>"+item['fname']+"</th><th>"+item['lname']+"</th><th>"+item['role']+"</th><th><img width='100' height='70'src='"+item['img']+"'></th><th><button value='"+item['id']+"' onClick='see_profile(this.value)'>Посмотреть</button>";
                  if (!role.localeCompare("admin")) {
                    str+="<p><button value='"+item['id']+"' onClick='edit_profile(this.value)'>Редактировать</button></p><p><button value='"+item['id']+"' onClick='delete_profile(this.value)'>Удалить</button></p>"
                  }
                  str+="</th></tr>";
                  document.getElementById('search_table').innerHTML+=str;
                  })
                  document.getElementById('search_table').innerHTML+="</table>";
           			}
         			});
			}else{
				alert("Поле для поиска пустое");
			}
		}

		function exit_from_profile(argument) {
			$.ajax({
          		type: "POST",
          		url: "Route.php",
           		data: {
                model:'user',
                action:'exit'
           		},
           		success: function(data){
           			document.getElementById('login').value='';
           			document.getElementById('password').value='';
           			createTable();
           		}
         	});
		}
		function see_profile(argument) {
			$.ajax({
           type: "POST",
           url: "Route.php",
           data: {
            model:'user',
            action:'save_id_see',
           	see_profile:argument
           },
           success: function(data)
           {
           	location.href="profile.php";
           }
         });
		}
		function edit_profile(argument) {                     
			$.ajax({
           type: "POST",
           url: "Route.php",
           data: {
            model:'user',
            action:'save_id_edit',
           	edit:argument
           },
           success: function(data)
           {
           	location.href="change_info.php";
           }
         });
		}
		function delete_profile(argument) {
			$.ajax({
           type: "POST",
           url: "Route.php",
           data: {
            model:'user',
            action:'delete',
           	delete:argument
           },
           success: function(data)
           {
           	createTable();
           }
         });
		}
		
    $(document).ready(function(){
        // =validation
        //alert("ok");
        $("#log_in").validate({
            submitHandler: function(form){
                //alert("ok1");
                var form = document.forms[0],
                    formData = new FormData(form),
                    xhr = new XMLHttpRequest();
                //alert("ok2");
                formData.append('model','user');
                formData.append('action','log_in');
                console.log(document.forms);
                xhr.open("POST", "Route.php");
                xhr.responseType = 'text';
                 xhr.onreadystatechange = function() {
                     if (xhr.readyState == 4) {
                         if(xhr.status == 200) {
                             // alert("ok3");
                             if (xhr.responseText)
                             alert(xhr.responseText);
                         	else{
                         			createTable();
                         	}
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
		<form id="log_in">
		login
		<input type="text" id="login" name="login" required>
		password
		<input type="password" id="password" name="password" required>
		<input type="submit" value="Войти" name="log_in">	
		<p>
			<input type="button" value="Зарегистрироватся" onClick="location.href='register.php'">
		</p>
		</form>
		<div id="user_log_in" hidden>
			<input type="button" id="admin_reg" value="Зарегистрировать" onClick="location.href='register.php'">
      <!-- <button onclick="edit_profile(this.value)">Редактировать профиль</button> -->
			<input type="button" value="Редактировать профиль" onClick="location.href='change_info.php'">
			<button onclick="exit_from_profile()">Выйти</button>
		</div>
	</p>
	<p>
		<input type="text" id="search">
		<button onclick="search()">Найти</button>
	</p>
  <p>
    <button onclick="sort()">Сортировка</button>
  </p>
	<p>
		<table border="1" id="search_table"></table>
	</p>
	<p>
	 <table border="1" id="table_users"></table>
	</p>
</body>
</html>

<style type="text/css">
	th {
		padding:6;
	}
</style>
