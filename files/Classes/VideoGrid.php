<?php
class VideoGrid{
    private $con;
    private $largeMode = false;
    private $gridClass = "videoGrid";

    public function __construct($con){
        $this->con= $con;
    }

    public function create($videos, $title, $showFilter,$loggedInUserName){
        if($videos == null && $title == "Recommended"){
            $gridItems= $this->getPublicItems('Public', $loggedInUserName);
        }

        else if($videos == null && $title == 'Suggestions' && $loggedInUserName==""){
            $gridItems = $this->getPublicSuggestions('Public');
        }

        else if($videos == null && $title == 'Suggestions' && $loggedInUserName!=""){
            $gridItems = $this->getPublicSuggestionsUserLoggedIn('Public', $loggedInUserName);
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

        else{
            $gridItems = $this->getItemsFromVideos($videos);
        }

        $header="";

        if($title != null){
            $header=$this->createGridHeader($title, $showFilter);
        }

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
            $item = new VideoItem($video, $this->largeMode);
            $element .= $item->create();
        }
        return $element;
    }

    public function getMyVideos($loggedInUserName){
        $query=$this->con->prepare("SELECT * FROM videos where uploadedBy = '$loggedInUserName'");
        $query->execute();
        $element="";
        while($row= $query->fetch(PDO::FETCH_ASSOC)){
            $video=new Video($this->con, $row);
            $item=new VideoItem($video, $this->largeMode);
            $element .= $item->create();
        }
        return $element;
    }

    public function getSpecialVideos($loggedInUserName){
        $fav='Favorite';
        $friend='Friend';
        $status='Not Blocked';
        $family="Family";

        $query=$this->con->prepare("SELECT * FROM videos inner join contact on videos.uploadedBy=contact.userName 
        where contactUserName='$loggedInUserName' and status='$status' and((contactType='$family' and videos.privacy='$family') or (contactType='$friend' and videos.privacy='$friend') or (contactType='$fav' and videos.privacy='$fav'))");

        $query->execute();

        $element="";
        while($row= $query->fetch(PDO::FETCH_ASSOC)){
            $video = new Video($this->con, $row);
            $item = new VideoItem($video, $this->largeMode);
            $element .= $item->create();
        }
        return $element;
    }

    public function getPublicItems($privacy,$loggedInUserName){
        $status='Not Blocked';
        $query=$this->con->prepare("SELECT * FROM videos inner join users on videos.uploadedBy=users.userName and users.userName !='$loggedInUserName' left outer join contact on users.userName=contact.userName and contactUserName='$loggedInUserName' where videos.privacy='$privacy' and (status is null or status='$status')");

        $query->execute();
        $element="";
        while($row= $query->fetch(PDO::FETCH_ASSOC)){
            $video=new Video($this->con, $row);
            $item=new VideoItem($video, $this->largeMode);
            $element .= $item->create();
        }
        return $element;
    }

    public function getPublicSuggestions($privacy){
        $query=$this->con->prepare("SELECT * FROM videos where privacy='$privacy' LIMIT 6");
        $query->execute();
        $element="";
        while($row= $query->fetch(PDO::FETCH_ASSOC)){
            $video=new Video($this->con, $row);
            $item=new VideoItem($video, $this->largeMode);
            $element .= $item->create();
        }
        return $element;
    }

    public function getPublicSuggestionsUserLoggedIn($privacy,$loggedInUserName){
        $status='Not Blocked';
        $query=$this->con->prepare("SELECT * FROM videos inner join users on videos.uploadedBy=users.userName and users.userName!='$loggedInUserName' left outer join contact on users.userName=contact.userName and contactUserName='$loggedInUserName' where privacy='$privacy' and (status is null or status='$status') LIMIT 6");
        $query->execute();
        $fav='Favorite';
        $friend='Friend';
        $status='Not Blocked';
        $family="Family";

        $query1=$this->con->prepare("SELECT * FROM videos inner join contact on videos.uploadedBy=contact.userName 
        where contactUserName='$loggedInUserName' and status='$status' and((contactType='$family' and privacy='$family') or (contactType='$friend' and privacy='$friend') or (contactType='$fav' and privacy='$fav'))");

        $query1->execute();

        $element="";
        while($row= $query->fetch(PDO::FETCH_ASSOC)){
            
            $video = new Video($this->con, $row);
            $item = new VideoItem($video, $this->largeMode);
            $element .= $item->create();
            //, $this->userLoggedInObj
            
        }
        if($query->rowCount()==0){
            while($row= $query1->fetch(PDO::FETCH_ASSOC)){
            
                $video = new Video($this->con, $row);
                $item = new VideoItem($video, $this->largeMode);
                $element .= $item->create();
            }
        }       
    return $element;
    }

    public function getItemsFromVideos($videos){
        $element = "";
        foreach($videos as $video) {
            $item = new VideoItem($video, $this->largeMode);
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

    public function modeLarge($videos, $title, $showFilter,$loggedInUserName){
            $this->gridClass .= " large";
            $this->largeMode= true;
            return $this->create($videos, $title, $showFilter, $loggedInUserName);
    }
}

?>