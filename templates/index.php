<?php 
require 'db_conn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>To-Do List</title>
    <style>
  body{
      font-family: Arial, Tahoma, Geneva, Brush Script MT, sans-serif;
      margin:0;
      background-image: url('https://media.istockphoto.com/photos/green-vintage-paper-background-picture-id1370125170?b=1&k=20&m=1370125170&s=170667a&w=0&h=SfzWL2txkVK4XnkNuFYnTEo_rA4qzAMyjJ6hJLfqRfU='); 
      background-size: 200% 200%;
      background-repeat: no-repeat;
      background-attachment: fixed; 
      background-size: 100% 100%;

}
  
  * {
    padding: 0px;
    margin: 0px;
    box-sizing: border-box;
  }
  
  .main-section {
    background: transparent;
    max-width: 500px;
    width: 90%;
    height: 500px;
    margin: 100px auto;
    border-radius: 10px;
  }
  
  .add-section {
    width: 100%;
    background: #fff;
    margin: 0px auto;
    padding: 10px;
    border-radius: 5px;
  }
  
  .add-section input {
    display: block;
    width: 95%;
    height: 40px;
    margin: 10px auto;
    border: 2px solid #ccc;
    font-size: 16px;
    border-radius: 5px;
    padding: 0px 5px;
  }
  
  .add-section button {
    display: block;
    width: 95%;
    height: 40px;
    margin: 0px auto;
    border: none;
    outline: none;
    background: #0088FF;
    color: #fff;
    font-family: sans-serif;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
  }
  
  .add-section button:hover {
    box-shadow: 0 2px 2px 0 #ccc, 0 2px 3px 0 #ccc;
    opacity: 0.7;
  }
  
  .add-section button span {
    border: 1px solid #fff;
    border-radius: 50%;
    display: inline-block;
    width: 18px;
    height: 18px;
  }
  
  #errorMes {
    display: block;
    background: #f2dede;
    width: 95%;
    margin: 0px auto;
    color: rgb(139, 19, 19);
    padding: 10px;
    height: 35px;
  }
  
  .show-todo-section {
    width: 100%;
    background: #fff;
    margin: 30px auto;
    padding: 10px;
    border-radius: 5px;
  }
  
  .todo-item {
    width: 95%;
    margin: 10px auto;
    padding: 20px 10px;
    box-shadow: 0 4px 8px 0 #ccc, 0 6px 20px 0 #ccc;
    border-radius: 5px;
  }
  
  .todo-item h2 {
    display: inline-block;
    padding: 5px 0px;
    font-size: 17px;
    font-family: sans-serif;
    color: #555;
  }
  
  .todo-item small {
    display: block;
    width: 100%;
    padding: 5px 0px;
    color: #888;
    padding-left: 30px;
    font-size: 14px;
    font-family: sans-serif;
  }
  
  .remove-to-do {
    display: block;
    float: right;
    width: 20px;
    height: 20px;
    font-family: sans-serif;
    color: rgb(139, 97, 93);
    text-decoration: none;
    text-align: right;
    padding: 0px 5px 8px 0px;
    border-radius: 50%;
    transition: background 1s;
    cursor: pointer;
  }
  
  .remove-to-do:hover {
    background: rgb(139, 97, 93);
    color: #fff;
  }
  
  .checked {
    color: #999 !important;
    text-decoration: line-through;
  }
  
  .todo-item input {
    margin: 0px 5px;
  }
  
  .empty {
    font-family: sans-serif;
    font-size: 16px;
    text-align: center;
    color: #cccc;
  }
  #header ul li{
    display: inline;
    position:relative;
    top:5px;
}
#header ul li a{
	border:2px solid;
	font-size:15px;
	float:right;
	background-color:#ff80aa;
	width:90px;
	height:40px;
	text-align:center;
	margin:10px 20px 0px 20px;
	font-weight:bold;
	line-height:40px;
	border-radius:50px;
	transition-property:all;
	transition-duration:0.4s;
	transition-timing-function:linear;
}	
#header ul li a{
	text-decoration:none;
	color:white;
}
#header ul li a:hover{
        background-color:#ffa64d;
        color:#994d00;
		border-color: #994d00;	
}
#header ul li a:active{
background-color:lightgreen;
}
    </style>
</head>
<body>
<div id="header" style="background-color: rgba(0, 0, 255, 0.2); width: 100%; height: 65px;">

			<ul>
      <li><a href="http://127.0.0.1:5000/contact_us">Contact us</a></li>
				<li><a href="http://127.0.0.1:5000/feedback">Feedback</a></li>
				<li><a href="http://127.0.0.1:8080/login/templates/index.php">To-do</a></li>
				<li><a href="http://127.0.0.1:5000/account">Account</a></li>
				<li><a href="http://127.0.0.1:5000/home">Home</a></li>
			</ul>

		</div>
    <div class="main-section">
       <div class="add-section">
          <form action="add.php" method="POST" autocomplete="off">
             <?php if(isset($_GET['mess']) && $_GET['mess'] == 'error'){ ?>
                <input type="text" 
                     name="title" 
                     style="border-color: #ff6666"
                     placeholder="This field is required" />
              <button type="submit">Add &nbsp; <span>&#43;</span></button>

             <?php }else{ ?>
              <input type="text" 
                     name="title" 
                     placeholder="What do you need to do?" />
              <button type="submit">Add &nbsp; <span>&#43;</span></button>
             <?php } ?>
          </form>
       </div>
       <?php 
          $todos = $conn->query("SELECT * FROM todos ORDER BY id DESC");
       ?>
       <div class="show-todo-section">
            <?php if($todos->rowCount() == 0){ ?>
                <div class="todo-item">
                    <div class="empty">
                        <img src="https://media.istockphoto.com/photos/tablet-with-paper-clip-todo-list-3d-illustration-3d-rendering-picture-id1285082518?b=1&k=20&m=1285082518&s=170667a&w=0&h=b-ByZ7VS2dnHJRvHUBxdp1qLoeNz_XVBGQK7X8eXgGU=" width="100%">
                    </div>
                </div>
            <?php } ?>

            <?php while($todo = $todos->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="todo-item">
                    <span id="<?php echo $todo['id']; ?>"
                          class="remove-to-do">x</span>
                    <?php if($todo['checked']){ ?> 
                        <input type="checkbox"
                               class="check-box"
                               data-todo-id ="<?php echo $todo['id']; ?>"
                               checked />
                        <h2 class="checked"><?php echo $todo['title'] ?></h2>
                    <?php }else { ?>
                        <input type="checkbox"
                               data-todo-id ="<?php echo $todo['id']; ?>"
                               class="check-box" />
                        <h2><?php echo $todo['title'] ?></h2>
                    <?php } ?>
                    <br>
                    <small>created: <?php echo $todo['date_time'] ?></small> 
                </div>
            <?php } ?>
       </div>
    </div>

    <script src="jquery-3.2.1.min.js"></script>

    <script>
        $(document).ready(function(){
            $('.remove-to-do').click(function(){
                const id = $(this).attr('id');
                
                $.post("remove.php", 
                      {
                          id: id
                      },
                      (data)  => {
                         if(data){
                             $(this).parent().hide(600);
                         }
                      }
                );
            });

            $(".check-box").click(function(e){
                const id = $(this).attr('data-todo-id');
                
                $.post('check.php', 
                      {
                          id: id
                      },
                      (data) => {
                          if(data != 'error'){
                              const h2 = $(this).next();
                              if(data === '1'){
                                  h2.removeClass('checked');
                              }else {
                                  h2.addClass('checked');
                              }
                          }
                      }
                );
            });
        });
    </script>
</body>
</html>