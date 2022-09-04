<?php

    function LoadJson($path){        
        return json_decode(file_get_contents($path, true));
    }

    function WriteJsonToDB($decodedJson, $tableName){ // Запись данных в БД
        $link = mysqli_connect("localhost", "root", "root", "phptest"); // Подключение к БД  
        $count = 0;    

        foreach ($decodedJson as $d){
            $array = get_object_vars($d); // "Превращаем" объект в массив
            $keys = array_keys($array); // Получаем имена свойств объекта 
            $sql = 'INSERT INTO ' . $tableName . ' (';
            foreach ($keys as $k){
                $sql .= ' ' . $k . ',';
            }
            $sql = substr($sql, 0, -1); // Удаляем лишнюю запятую
            $sql .= ') VALUES (';
            foreach ($keys as $k){
                $value = $array[$k];
                if (gettype($value) == "string") // Если тип значения строка, то нужно добавить одинарные ковычки, чтобы не было ошибок SQL запроса
                    $value = "'" . $value . "'";
                $sql .= ' ' . $value . ',';
            }
            $sql = substr($sql, 0, -1); // Удаляем лишнюю запятую
            $sql .= ');';

            //echo $sql;

            $result = mysqli_query($link, $sql);

            if ($result == false) {
                echo  "Произошла ошибка при выполнении запроса";
            }
            else {
                $count++;
            }            
        }

        mysqli_close($link);
        return $count;
    }

    function LoadRecordsAndComments(){ // 2. Создать PHP скрипт, который скачает список записей и комментариев к ним и загрузит их в БД. По завершению загрузки, вывести в консоль надпись: "Загружено Х записей и Y комментариев"
        $postsCount = WriteJsonToDB(LoadJson('https://jsonplaceholder.typicode.com/posts'), 'posts');
        $commentsCount = WriteJsonToDB(LoadJson('https://jsonplaceholder.typicode.com/comments'), 'comments');
        echo  "Загружено " . $postsCount . " записей и " . $commentsCount . " комментариев";
    }

    function Find($pattern){
        $link = mysqli_connect("localhost", "root", "root", "phptest"); // Подключение к БД 
        $sql = "SELECT posts.title, comments.body FROM comments INNER JOIN posts ON comments.postId=posts.id WHERE comments.body like '%" .  $pattern ."%';";

        $count = 0;
        echo "<div class=\"block\">Finding \"" . $pattern . "\"</div>";
        if($result = mysqli_query($link, $sql)){
            foreach($result as $row){  
                $count++;       
                $title = $row["title"];
                $body = $row["body"];
                echo "<div class=\"block\">#". $count ."<br><span class=\"title\">" . $title . 
                "</span><br>" . $body ."<br></div>";                
            }
        }
        echo "<div class=\"block\">Count: " . $count . "</div>";
    }