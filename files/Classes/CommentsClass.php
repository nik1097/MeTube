<?php

class CommentsClass{
    private $con;
    public function __construct($con){
        $this->con = $con;
    }

    public function getAllCommentsOfVideo($videoId){
        try{
            $query=$this->con->prepare("select * from comments where videoId='$videoId' order by commentedDate");
            $query->execute();
            if($query->rowCount()==0){
                return "";
            }
            else{
                $html="";
                while($row=$query->fetch(PDO::FETCH_ASSOC)){
                    $comment=$row['comment'];
                    $postedBy=$row['postedBy'];
                    $comentedDate =$row['commentedDate'];
                    $html.="<div style='margin-right:425px;' class='container1'><span class='text-success'>$postedBy</span><p>$comment</p>  <span class='time-right'>$comentedDate</span></div>";
                }
              return $html;
            }
        }

        catch(Exception $e){
            echo"Some Error Occured: ".$e->getMessage();
        }
    }

    public function postComment($postedBy,$videoId,$comment){
        try{
            $query=$this->con->prepare("insert into comments (postedBy,comment,videoId) values ('$postedBy','$comment','$videoId')");
            $query->execute();
        }
        catch(Exception $e){
            echo"Some Error Occured: ".$e->getMessage();
        }
        
    }
    }

?>