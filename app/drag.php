<?php
    if(isset($_POST['values']) && $_POST['values'] != 0){
        require '../db_conn.php';
        $len = $_POST['values'];
        $order = [];
        for($i = 0; $i < $len; $i++){
            array_push($order, $_POST[(string)($i)]);
        }

        if(empty($order)){
            echo 'error';
        } else {
            for($i = 0;$i < $len; $i++){
                $todos = $conn->prepare("SELECT id, position FROM todos WHERE id=?");
                $todos->execute([$order[$i]]);
                $todo = $todos->fetch();
                if($todo){
                    $todoId = $todo['id'];
                    $todoPosition = $todo['position'];

                    $stmt = $conn->prepare("UPDATE todos SET position=$i WHERE id=?"); 
                    $res = $stmt->execute([$order[$i]]);
                    if($res){
                        echo $i+1;
                    }else {
                        echo "error";
                    }
                }else {
                    echo 'error';
                }
            }
            $conn = null;
            exit();
        }
    }else {
        header("Location: ../index.php?mess=error");
    }
?>