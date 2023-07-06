<?php
    if(isset($_POST['id'])){
        require '../db_conn.php';
        $id = $_POST['id'];

        if(empty($id)){
            echo 'error';
        } else {
            $todos = $conn->prepare("SELECT id, is_checked FROM todos WHERE id=?");
            $todos->execute([$id]);
            $todo = $todos->fetch();
            $todoId = $todo['id'];
            $todoChecked = $todo['is_checked'];
            $checked = $todoChecked ? 0 : 1;

            $stmt = $conn->prepare("UPDATE todos SET is_checked=$checked WHERE id=?"); 
            $res = $stmt->execute([$todoId]);

            if($res){
                echo $checked;
            }else {
                echo "error";
            }
            $conn = null;
            exit();
        }
    }else {
        header("Location: ../index.php?mess=error");
    }
?>