<?php
    if(isset($_POST['id'])){
        require '../db_conn.php';
        $id = $_POST['id'];

        if(empty($id)){
            echo 'error';
        } else {
            $validColors = ['colorRed', 'colorGreen', 'colorBlue', 'colorYellow'];
            $todos = $conn->prepare("SELECT id, color FROM todos WHERE id=?");
            $todos->execute([$id]);
            $todo = $todos->fetch();
            $todoId = $todo['id'];
            $todoColor = $todo['color'];
            $idx = array_search($todoColor, $validColors);
            $nextColor;
            if(isset($validColors[$idx+1])) {
                $nextColor = $validColors[$idx+1]; 
            } else {
                $nextColor = $validColors[0];
            }
            
            $stmt = $conn->prepare("UPDATE todos SET color=? WHERE id=?"); 
            $res = $stmt->execute([$nextColor, $todoId]);

            if($res){
                echo $nextColor;
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