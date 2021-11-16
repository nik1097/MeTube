<?php
class MediaGrid{
    private $con;
    private $gridClass = "videoGrid";

    public function __construct($con){
        $this->con= $con;
    }

    public function create($media, $title, $showFilter,$loggedInUserName){
        $categoryList = array("Animal", "Sports", "Other", "Human");
        if($media == null && $title == "Recommended"){
            $gridItems= $this->getPublicItems('Public', $loggedInUserName);
        }
        else if($media == null && $title == 'My Channel'){
            $gridItems = $this->getMyMedia($loggedInUserName);
        }

        else if($media == null && $title == 'My Favorites'){
            $gridItems = $this->getFavorite($loggedInUserName);
        }

        else if($media == null && $title == 'Shared Media'){
            $gridItems = $this->getSpecialMedia($loggedInUserName);
        }

        else if($media == null && $title =='All Media'){
            $gridItems = $this->getItems('Public');
        }

        else if($media == null && in_array($title , $categoryList)){
            $gridItems = $this->getCategory($loggedInUserName, $title);
        }
        $header="";
        $header=$this->createGridHeader($title, $showFilter);
        return "$header
        <div class='$this->gridClass'> 
        $gridItems
        </div>";
    }


    public function getItems($privacy){
        //$query=$this->con->prepare("SELECT * FROM videos where privacy = '$privacy'");
        $query=$this->con->prepare("SELECT * FROM media where privacy = '$privacy'");
        $query->execute();

        $element="";
        while($row= $query->fetch(PDO::FETCH_ASSOC)){
            $media = new Media($this->con, $row);
            $item = new MediaItem($media);
            $element .= $item->create();
        }
        return $element;
    }

    public function getMyMedia($loggedInUserName){
        //$query=$this->con->prepare("SELECT * FROM videos where uploadedBy = '$loggedInUserName'");
        $query=$this->con->prepare("SELECT * FROM media where uploadedBy = '$loggedInUserName'");
        $query->execute();

        $element="";
        while($row= $query->fetch(PDO::FETCH_ASSOC)){
            $media = new Media($this->con, $row);
            $item = new MediaItem($media);
            $element .= $item->create();
        }
        return $element;
    }

    public function getFavorite($loggedInUserName){
        //$query=$this->con->prepare("SELECT videos.* FROM videos inner join favorites on videos.id=favorites.videoId where favorites.userName='$loggedInUserName'");
        $query=$this->con->prepare("SELECT media.* FROM media inner join favorites on media.id=favorites.videoId where favorites.userName='$loggedInUserName'");
        $query->execute();

        $element="";
        while($row= $query->fetch(PDO::FETCH_ASSOC)){
            $media=new Media($this->con, $row);
            $item=new MediaItem($media);
            $element .= $item->create();
        }
        return $element;
    }

    public function getSpecialMedia($loggedInUserName){

//        $query=$this->con->prepare("SELECT videos.* FROM videos inner join contact on videos.uploadedBy=contact.userName
//        where contactUserName='$loggedInUserName' and((contactType='Family' and videos.privacy='Family') or (contactType='Friend' and videos.privacy='Friend') or (contactType='Fav' and videos.privacy='Fav'))");

        $query=$this->con->prepare("SELECT media.* FROM media inner join contact on media.uploadedBy=contact.userName 
                                where contactUserName='$loggedInUserName' and((contactType='Family' and media.privacy='Family') 
                                              or (contactType='Friend' and media.privacy='Friend') 
                                              or (contactType='Fav' and media.privacy='Fav'))");

        $query->execute();

        $element="";
        while($row= $query->fetch(PDO::FETCH_ASSOC)){
            $media = new Media($this->con, $row);
            $item = new MediaItem($media);
            $element .= $item->create();
        }
        return $element;
    }

    public function getPublicItems($privacy,$loggedInUserName){
//        $query=$this->con->prepare("SELECT videos.* FROM videos inner join users on videos.uploadedBy=users.userName
//                                                    and users.userName !='$loggedInUserName' left outer join contact on users.userName=contact.userName
//                                                        and contactUserName='$loggedInUserName' where videos.privacy='$privacy'");

        $query=$this->con->prepare("SELECT media.* FROM media inner join users on media.uploadedBy=users.userName 
                                                    and users.userName !='$loggedInUserName' left outer join contact on users.userName=contact.userName 
                                                        and contactUserName='$loggedInUserName' where media.privacy='$privacy'");
        $query->execute();

        $element="";
        while($row= $query->fetch(PDO::FETCH_ASSOC)){
            $media=new Media($this->con, $row);
            $item=new MediaItem($media);
            $element .= $item->create();
        }
        return $element;
    }
    public function getCategory($loggedInUserName, $category){
        if ($loggedInUserName == ""){
            $query=$this->con->prepare("SELECT * FROM media where privacy = 'Public' and category = '$category'");
        } else {
            $query=$this->con->prepare("SELECT media.* FROM media 
                                    inner join users on media.uploadedBy=users.userName and users.userName !='$loggedInUserName' 
                                    left outer join contact on users.userName=contact.userName and contactUserName='$loggedInUserName' 
                                    where media.category='$category'");
        }

        $query->execute();

        $element="";
        while($row= $query->fetch(PDO::FETCH_ASSOC)){
            $video=new Media($this->con, $row);
            $item=new MediaItem($video);
            $element .= $item->create();
        }
        return $element;
    }
    public function getMostViews($loggedInUserName){
        $query=$this->con->prepare("SELECT media.* FROM media 
                                    inner join users on media.uploadedBy=users.userName and users.userName !='$loggedInUserName' 
                                    left outer join contact on users.userName=contact.userName and contactUserName='$loggedInUserName' 
                                    order by media.views desc");
        $query->execute();

        $element="";
        while($row= $query->fetch(PDO::FETCH_ASSOC)){
            $video=new Media($this->con, $row);
            $item=new MediaItem($video);
            $element .= $item->create();
        }
        return $element;
    }

    public function getRecentUploads($loggedInUserName){
        $query=$this->con->prepare("SELECT media.* FROM media 
                                    inner join users on media.uploadedBy=users.userName and users.userName !='$loggedInUserName' 
                                    left outer join contact on users.userName=contact.userName and contactUserName='$loggedInUserName' 
                                    order by media.uploadDate desc");
        $query->execute();

        $element="";
        while($row= $query->fetch(PDO::FETCH_ASSOC)){
            $video=new Media($this->con, $row);
            $item=new MediaItem($video);
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