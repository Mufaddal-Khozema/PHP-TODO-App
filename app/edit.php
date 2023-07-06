<?php
    if(isset($_POST['id']) && isset($_POST['text'])){
        require '../db_conn.php';
        $id = $_POST['id'];
        $text = $_POST['text'];

        if(empty($id)){
            echo 'error';
        } else {
            $todos = $conn->prepare("SELECT id, text FROM todos WHERE id=?");
            $todos->execute([$id]);
            $todo = $todos->fetch();
            if($todo){
                $todoId = $todo['id'];

                $stmt = $conn->prepare("UPDATE todos SET text=? WHERE id=?"); 
                $res = $stmt->execute([$text,$todoId]);
                if($res){
                    echo 1;
                }else {
                    echo "error";
                }
            }else {
                    echo 'error';
            }
        }
        $conn = null;
        exit();
    }else {
        header("Location: ../index.php?mess=error");
    }
?>