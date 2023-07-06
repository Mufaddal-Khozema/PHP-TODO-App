<?php
    if(isset($_POST['text'])){
        require '../db_conn.php';
        $text = $_POST['text'];
        echo $text;

        if(empty($text)){
            header("Location: ../index.php?mess=error");
        } else {
            $curLastItemResult = $conn->query("SELECT MAX(position) FROM todos");
            $curLastItem = $curLastItemResult->fetch(PDO::FETCH_ASSOC);
            $curLastItemPosition = $curLastItem['MAX(position)'];
            $nextItemPosition = $curLastItemPosition + 1;

            echo "<br>" . $nextItemPosition;
            $stmt = $conn->prepare("INSERT INTO todos(text,color,position) VALUE(?,?,?)");
            $res = $stmt->execute([$text,"colorRed",$nextItemPosition]);

            if($res){
                header("Location: ../index.php?mess=success");
            }else {
                header("Location: ../index.php");
            }
            $conn = null;
            exit();
        }
    }else {
        header("Location: ../index.php?mess=error");
    }
?>