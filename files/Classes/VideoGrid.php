<?php
class VideoGrid{
    private $con;
    private $gridClass = "videoGrid";

    public function __construct($con){
        $this->con= $con;
    }

    public function create($videos, $title, $showFilter,$loggedInUserName){
        if($videos == null && $title == "Recommended"){
            $gridItems= $this->getPublicItems('Public', $loggedInUserName);
        }
        else if($videos == null && $title == 'My Videos'){
            $gridItems = $this->getMyVideos($loggedInUserName);
        }

        else if($videos == null && $title == 'Shared Videos'){
            $gridItems = $this->getSpecialVideos($loggedInUserName);
        }

        else if($videos == null && $title =='All Videos'){
            $gridItems = $this->getItems('Public');
        } 
        
        $header="";
        $header=$this->createGridHeader($title, $showFilter);
        return "$header
        <div class='$this->gridClass'> 
        $gridItems
        </div>";
    }


    public function getItems($privacy){
        $query=$this->con->prepare("SELECT * FROM videos where privacy = '$privacy'");
        $query->execute();

        $element="";
        while($row= $query->fetch(PDO::FETCH_ASSOC)){
            $video = new Video($this->con, $row);
            $item = new VideoItem($video);
            $element .= $item->create();
        }
        return $element;
    }

    public function getMyVideos($loggedInUserName){
        $query=$this->con->prepare("SELECT * FROM videos where uploadedBy = '$loggedInUserName'");
        $query->execute();

        $element="";
        while($row= $query->fetch(PDO::FETCH_ASSOC)){
            $video = new Video($this->con, $row);
            $item = new VideoItem($video);
            $element .= $item->create();
        }
        return $element;
    }

    public function getSpecialVideos($loggedInUserName){

        $query=$this->con->prepare("SELECT videos.* FROM videos inner join contact on videos.uploadedBy=contact.userName 
        where contactUserName='$loggedInUserName' and((contactType='Family' and videos.privacy='Family') or (contactType='Friend' and videos.privacy='Friend') or (contactType='Fav' and videos.privacy='Fav'))");

        $query->execute();

        $element="";
        while($row= $query->fetch(PDO::FETCH_ASSOC)){
            $video = new Video($this->con, $row);
            $item = new VideoItem($video);
            $element .= $item->create();
        }
        return $element;
    }

    public function getPublicItems($privacy,$loggedInUserName){
        $query=$this->con->prepare("SELECT videos.* FROM videos inner join users on videos.uploadedBy=users.userName and users.userName !='$loggedInUserName' left outer join contact on users.userName=contact.userName and contactUserName='$loggedInUserName' where videos.privacy='$privacy'");

        $query->execute();

        $element="";
        while($row= $query->fetch(PDO::FETCH_ASSOC)){
            $video=new Video($this->con, $row);
            $item=new VideoItem($video);
            $element .= $item->create();
        }
        return $element;
    }

    public function createGridHeader($title, $showFilter){
        $filter = "";
        if($showFilter){
            $link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $urlArray = parse_url($link);
            $query = $urlArray["query"];
            parse_str($query, $params);
            unset($params["orderBy"]);
            $newQuery = http_build_query($params);
            $newUrl = basename($_SERVER["PHP_SELF"]) . "?" . $newQuery;
            $filter = "<div class='right'>
                            <span>Order by:</span>
                            <a href='$newUrl&orderBy=uploadDate'>Upload Date</a>
                            <a href='$newUrl&orderBy=Views'>Most Viewed</a>
                            <a href='$newUrl&orderBy=Title'>Sort by Title</a>
                        </div>";
        }
        return "<div class='videoGridHeader'> 
                <div class='left'>
                    $title
                </div>
                $filter
        </div>";
    }
}

?>