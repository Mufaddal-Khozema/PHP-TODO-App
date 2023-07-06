<?php
require 'db_conn.php';
?>
<!DOCTYPE html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=us-ascii" />
  <link rel="stylesheet" href="style.css" type="text/css" />
  <title></title>
</head>

<body>
  <div id="page-wrap">
    <div id="header">
      <h1><a href="">PHP Sample Test App</a></h1>
    </div>
    <?php 
      
      $todos = $conn->query("SELECT * FROM todos ORDER BY position ASC");
    ?>
    <div id="main">
      <noscript>This site just doesn't work, period, without JavaScript</noscript>
      <ul id="list" class="ui-sortable">

        <?php while($todo = $todos->fetch(PDO::FETCH_ASSOC)) { ?>
        <li data-color="<?php echo $todo['color']?>" class="<?php echo $todo['color']?> item" rel="1" id="<?php echo $todo['id']?>">

          <div id="<?php echo $todo['position']?>" class="draggertab tab" draggable="true"></div>

          <div id="<?php echo $todo['id']?>" class="colortab tab"></div>

          <span id="<?php echo $todo['id']?>" class="<?php if($todo['is_checked'] === "1") echo "crossout" ?> tab" title="Double-click to edit..." >
            
            <?php echo $todo['text']?>

          </span>

          <div id="<?php echo $todo['id']?>" class="donetab tab"></div>
          
          <div id="<?php echo $todo['id']?>" class="deletetab tab"></div>

        </li>
        <?php }?>

        <!-- <li class="colorGreen" rel="2" id="4">
          <div class="draggertab tab"></div>
          <div class="colortab tab"></div>

          <span id="4listitem" class="crossout" title="Double-click to edit...">
            Saibaan List
          </span>

          <div class="deletetab tab"></div>
          <div class="donetab tab"></div>
        </li>

        <li color="1" class="colorBlue" rel="3" id="6">
          <div class="draggertab tab"></div>
          <div class="colortab tab"></div>
          <span id="6listitem" class="tab" title="Double-click to edit...">adfas</span>
          <div class="deletetab tab"></div>
          <div class="donetab tab"></div>
        </li>-->
      </ul> 
	  <br />
          
      <form action="app/add.php" id="add-new" method="post" autocomplete="off">
        <?php if(isset($_GET['mess']) && $_GET['mess'] === "error") {?>
          <input type="text" id="new-list-item-text" style="border-color:#ff6666" name="text" placeholder="This field is required"/>
        <?php } else {?>
        <input type="text" id="new-list-item-text" name="text" placeholder="What do you want to do"/>
        <?php }?>
        <input type="submit" id="add-new-submit" value="Add" class="button" />
      </form>

      <div class="clear"></div>
    </div>
  </div>
</body>
<script src="script.js" type="module"></script>
</html>
