<?php
class MediaGrid
{
    private $con;
    private $gridClass = "videoGrid";

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function createGridHeader($title, $hide = 'not hidden')
    {
        $filter = "";
        if ($hide != 'hidden') {
            $filter = "<div class='right'>
                        <span>Order by:</span>
                        <a href='http://localhost/MeTube/search.php?term=&orderBy=uploadDate'>Upload Date</a>
                        <a href='http://localhost/MeTube/search.php?term=&orderBy=Views'>Most Viewed</a>
                        <a href='http://localhost/MeTube/search.php?term=&orderBy=Title'>Sort by Title</a>
                    </div>";
            return "<div class='videoGridHeader'> 
                <div class='left'>
                    $title
                </div>
                $filter
        </div>";
        }
    }

    public function createBlock($headerTitle, $gridItems)
    {
        $header = $this->createGridHeader($headerTitle);
        return "$header
                <div class='$this->gridClass'> 
                    $gridItems
                </div>";
    }

    public function createHomeContentBlock($type, $category, $sortBy, $loggedInUserName, $hide = 'not hidden')
    {
        if ($loggedInUserName == "") {
            $gridItems = $this->getAllItemsVisitor($type, $category, $sortBy, 'Public');
            return $this->createBlock($type."s", $gridItems);
        } else {
            $gridItems = $this->getPublicItemsUser($type, $category, $sortBy, 'Public', $loggedInUserName);
            return $this->createBlock($type."s", $gridItems);
        }
    }

    public function createSearchContentBlock($type, $keywords, $sortBy, $loggedInUserName, $hide = 'not hidden')
    {
        if ($loggedInUserName == "") {
            $gridItems = $this->getKeywordsItemsVisitor($type, $keywords, $sortBy, 'Public');
            return $this->createBlock($type."s from '".$keywords."'", $gridItems);
        } else {
            $gridItems = $this->getKeywordsItemsUser($type, $keywords, $sortBy, 'Public', $loggedInUserName);
            return $this->createBlock($type."s from '".$keywords."'", $gridItems);
        }
    }

    public function createMyMediaContentBlock($type, $sortBy, $loggedInUserName, $hide = 'not hidden')
    {
        $gridItems = $this->getMyMedia($type, $sortBy, $loggedInUserName);
        return $this->createBlock("My ".$type."s", $gridItems);
    }

    public function createMySharedContentBlock($type, $sortBy, $loggedInUserName, $hide = 'not hidden')
    {
        $gridItems = $this->getSharedMedia($type, $sortBy, $loggedInUserName);
        return $this->createBlock("Shared ".$type."s", $gridItems);
    }

    public function createMyFavoriteContentBlock($type, $sortBy, $loggedInUserName, $hide = 'not hidden')
    {
        $gridItems = $this->getFavorite($type, $sortBy, $loggedInUserName);
        return $this->createBlock("My Favorite ".$type."s", $gridItems);
    }

    public function create($page, $category, $keywords, $sortBy, $loggedInUserName, $videoId = 1, $hide = 'not hidden')
    {
        if ($page == 'Home') {
            return $this->createHomeContentBlock('video', $category, $sortBy, $loggedInUserName, $hide = 'not hidden')
                . $this->createHomeContentBlock('image', $category, $sortBy, $loggedInUserName, $hide = 'not hidden');
        }

        if ($page == 'MyChannel') {
            return $this->createMyMediaContentBlock('video',  $sortBy, $loggedInUserName, $hide = 'not hidden')
                . $this->createMyMediaContentBlock('image',  $sortBy, $loggedInUserName, $hide = 'not hidden')
                . $this->createMySharedContentBlock('video',  $sortBy, $loggedInUserName, $hide = 'not hidden')
                . $this->createMySharedContentBlock('image',  $sortBy, $loggedInUserName, $hide = 'not hidden');
        }

        if ($page == 'Search') {
            return $this->createSearchContentBlock('video', $keywords, $sortBy, $loggedInUserName, $hide = 'not hidden')
                . $this->createSearchContentBlock('image', $keywords, $sortBy, $loggedInUserName, $hide = 'not hidden');
        }

        if ($page == 'Recommendation') {
            return $this->getSuggestions($videoId);
        }

        if ($page == 'MyList') {

        }

        if ($page == 'Favorite') {
            return $this->createMyFavoriteContentBlock('video', $sortBy, $loggedInUserName)
                  .$this->createMyFavoriteContentBlock('image', $sortBy, $loggedInUserName);
        }
    }

    public function getSuggestions($videoId)
    {
        $query = $this->con->prepare("SELECT * FROM media where id = '$videoId'");
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $media = new Media($this->con, $row);
        $mediaCategory = $media->getCategory();
        $mediaOwner = $media->getUploadedBy();

        $query = $this->con->prepare("SELECT * FROM media where media.category = '$mediaCategory' and media.uploadedBy != '$mediaOwner' 
                                    and privacy = 'Public' order by rand() limit 4");

        //$query = $this->con->prepare("SELECT * FROM media where privacy = '$privacy' order by rand() limit 4");
        $query->execute();

        $element = "";
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $media = new Media($this->con, $row);
            $item = new MediaItem($media);
            $element .= $item->create();
        }
        return $element;
    }

    public function getAllItemsVisitor($type, $category, $sortby, $privacy)
    {
        $sqlString = "SELECT media.* FROM media where media.privacy='$privacy' and media.mediaType = '$type'";
        if ($category != "") {
            $sqlString .= "and media.category = '$category'";
        }
        if ($sortby != "") {
            $sqlString .= "order by '$sortby'";
        }

        $query = $this->con->prepare($sqlString);
        $query->execute();

        $element = "";
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $media = new Media($this->con, $row);
            $item = new MediaItem($media);
            $element .= $item->create();
        }
        return $element;
    }

    public function getPublicItemsUser($type, $category, $sortby, $privacy, $loggedInUserName)
    {
        $sqlString = "SELECT media.* FROM media 
                                    inner join users on media.uploadedBy=users.userName and users.userName !='$loggedInUserName' 
                                    left outer join contact on users.userName=contact.userName and contactUserName='$loggedInUserName' 
                                    where media.privacy='$privacy' and media.mediaType = '$type' and (status!='Blocked' or status is NULL)";
        if ($category != "") {
            $sqlString .= "and media.category = '$category'";
        }
        if ($sortby != "") {
            $sqlString .= "order by '$sortby'";
        }

        $query = $this->con->prepare($sqlString);

        $query->execute();

        $element = "";
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $media = new Media($this->con, $row);
            $item = new MediaItem($media);
            $element .= $item->create();
        }
        return $element;
    }

    public function getKeywordsItemsVisitor($type, $keywords, $sortby, $privacy)
    {
        $sqlString = "SELECT media.* FROM media 
                      inner join keywords on media.id = keywords.media_id
                      where media.privacy='$privacy' and media.mediaType = '$type' and keywords.keyword = '$keywords'";

        if ($sortby != "") {
            $sqlString .= "order by '$sortby'";
        }

        $query = $this->con->prepare($sqlString);
        $query->execute();

        $element = "";
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $media = new Media($this->con, $row);
            $item = new MediaItem($media);
            $element .= $item->create();
        }
        return $element;
    }

    public function getKeywordsItemsUser($type, $keywords, $sortby, $privacy, $loggedInUserName)
    {
        $sqlString = "SELECT media.* FROM media 
                      inner join keywords on media.id = keywords.media_id
                      inner join users on media.uploadedBy=users.userName and users.userName !='$loggedInUserName' 
                      left outer join contact on users.userName=contact.userName and contactUserName='$loggedInUserName'
                      where media.privacy='$privacy' and media.mediaType = '$type' and (status!='Blocked' or status is NULL) and keywords.keyword = '$keywords'";

        if ($sortby != "") {
            $sqlString .= "order by '$sortby'";
        }

        $query = $this->con->prepare($sqlString);

        $query->execute();

        $element = "";
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $media = new Media($this->con, $row);
            $item = new MediaItem($media);
            $element .= $item->create();
        }
        return $element;
    }

    public function getMyMedia($type, $sortBy, $loggedInUserName)
    {

        $query = $this->con->prepare("SELECT * FROM media where uploadedBy = '$loggedInUserName' and media.mediaType = '$type'");
        $query->execute();

        $element = "";
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $media = new Media($this->con, $row);
            $item = new MediaItem($media);
            $element .= $item->create();
        }
        return $element;
    }

    public function getFavorite($type, $sortBy, $loggedInUserName)
    {
        $query = $this->con->prepare("SELECT media.* FROM media 
                                    inner join favorites on media.id=favorites.videoId 
                                    where favorites.userName='$loggedInUserName'
                                    and media.mediaType = '$type'");
        $query->execute();

        $element = "";
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $media = new Media($this->con, $row);
            $item = new MediaItem($media);
            $element .= $item->create();
        }
        return $element;
    }

    public function getSharedMedia($type, $sortBy, $loggedInUserName)
    {
        $query = $this->con->prepare("SELECT media.* FROM media inner join contact on media.uploadedBy=contact.userName 
                                where contactUserName='$loggedInUserName' 
                                and media.mediaType = '$type'
                                and ((contactType='Family' and media.privacy='Family') 
                                     or (contactType='Friend' and media.privacy='Friend') 
                                     or (contactType='Fav' and media.privacy='Fav')) 
                                and status = 'Not Blocked'");

        $query->execute();

        $element = "";
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $media = new Media($this->con, $row);
            $item = new MediaItem($media);
            $element .= $item->create();
        }
        return $element;
    }


}


?>