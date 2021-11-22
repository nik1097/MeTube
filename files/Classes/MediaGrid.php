<?php
class MediaGrid
{
    private $con;
    private $gridClass = "videoGrid";

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function createGridHeader($title)
    {
        $filter = "";

        return "<div class='videoGridHeader'> 
                    <div class='left'>
                        $title
                    </div>

                </div>";

    }

    public function getKeywords($mediaId)
    {
        $query = $this->con->prepare("SELECT * FROM keywords where media_id = '$mediaId'");
        $query->execute();

        $keywords = "";
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $keywords.=$row["keyword"];
            $keywords.=" ";
        }
        $keywords = rtrim( $keywords, ' ');
        return $keywords;
    }

    public function createBlock($headerTitle, $gridItems)
    {
        $header = $this->createGridHeader($headerTitle);
        return "$header
                <div class='$this->gridClass'> 
                    $gridItems
                </div>";
    }

    public function createHomeContentBlock($type, $category, $sortby, $loggedInUserName, $hide = 'not hidden')
    {
        if ($loggedInUserName == "") {
            $gridItems = $this->getAllItemsVisitor($type, $category, $sortby, 'Public');
            return $this->createBlock($type."s", $gridItems);
        } else {
            $gridItems = $this->getPublicItemsUser($type, $category, $sortby, 'Public', $loggedInUserName);
            return $this->createBlock($type."s", $gridItems);
        }
    }

    public function createSearchContentBlock($type, $keywords, $sortby, $loggedInUserName, $size)
    {
        if ($loggedInUserName == "") {
            $gridItems = $this->getKeywordsItemsVisitor($type, $keywords, $sortby, 'Public', $size);
            return $this->createBlock($type."s from '".$keywords."'", $gridItems);
        } else {
            $gridItems = $this->getKeywordsItemsUser($type, $keywords, $sortby, 'Public', $loggedInUserName, $size);
            return $this->createBlock($type."s from '".$keywords."'", $gridItems);
        }
    }

    public function createMyMediaContentBlock($type, $sortby, $loggedInUserName, $hide = 'not hidden')
    {
        $gridItems = $this->getMyMedia($type, $sortby, $loggedInUserName);
        return $this->createBlock("My ".$type."s", $gridItems);
    }

    public function createMySharedContentBlock($type, $sortby, $loggedInUserName, $hide = 'not hidden')
    {
        $gridItems = $this->getSharedMedia($type, $sortby, $loggedInUserName);
        return $this->createBlock("Shared ".$type."s", $gridItems);
    }

    public function createMyFavoriteContentBlock($type, $sortby, $loggedInUserName, $hide = 'not hidden')
    {
        $gridItems = $this->getFavorite($type, $sortby, $loggedInUserName);
        return $this->createBlock("My Favorite ".$type."s", $gridItems);
    }

    public function create($page, $category, $keywords, $sortby, $loggedInUserName, $size = '100kB', $videoId = 1)
    {
        if ($page == 'Home') {
            return $this->createHomeContentBlock('video', $category, $sortby, $loggedInUserName, $hide = 'not hidden')
                . $this->createHomeContentBlock('image', $category, $sortby, $loggedInUserName, $hide = 'not hidden');
        }

        if ($page == 'MyChannel') {
            return $this->createMyMediaContentBlock('video',  $sortby, $loggedInUserName, $hide = 'not hidden')
                . $this->createMyMediaContentBlock('image',  $sortby, $loggedInUserName, $hide = 'not hidden')
                . $this->createMySharedContentBlock('video',  $sortby, $loggedInUserName, $hide = 'not hidden')
                . $this->createMySharedContentBlock('image',  $sortby, $loggedInUserName, $hide = 'not hidden');
        }

        if ($page == 'Search') {
            return $this->createSearchContentBlock('video', $keywords, $sortby, $loggedInUserName, $size)
                . $this->createSearchContentBlock('image', $keywords, $sortby, $loggedInUserName, $size);
        }

        if ($page == 'Recommendation') {
            return $this->getSuggestions($videoId);
        }

        if ($page == 'MyList') {

        }

        if ($page == 'Favorite') {
            return $this->createMyFavoriteContentBlock('video', $sortby, $loggedInUserName)
                  .$this->createMyFavoriteContentBlock('image', $sortby, $loggedInUserName);
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
            $sqlString .= "order by media.$sortby desc";
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
            $sqlString .= " and media.category = '$category'";
        }
        if ($sortby != "") {
            $sqlString .= " order by media.$sortby desc";
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

    public function getKeywordsItemsVisitor($type, $keywords, $sortby, $privacy, $size)
    {
        $sqlString = "SELECT media.* FROM media 
                      inner join keywords on media.id = keywords.media_id
                      where media.privacy='$privacy' and media.mediaType = '$type' and keywords.keyword = '$keywords'";

        if ($size != "") {
            if ($size == "0-100K") {
                $sqlString .= "and media.mediaSize < 102400 ";
            } else if ($size == "100K-1000K") {
                $sqlString .= "and media.mediaSize Between 102400 and 1024000 ";
            } else if ($size == ">1000K") {
                $sqlString .= "and media.mediaSize > 1024000 ";
            }
        }

        if ($sortby != "") {
            $sqlString .= "order by media.$sortby desc";
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

    public function getKeywordsItemsUser($type, $keywords, $sortby, $privacy, $loggedInUserName, $size)
    {
        $sqlString = "SELECT media.* FROM media 
                      inner join keywords on media.id = keywords.media_id
                      inner join users on media.uploadedBy=users.userName and users.userName !='$loggedInUserName' 
                      left outer join contact on users.userName=contact.userName and contactUserName='$loggedInUserName'
                      where media.privacy='$privacy' and media.mediaType = '$type' and (status!='Blocked' or status is NULL) and keywords.keyword = '$keywords'";
        if ($size != "") {
            if ($size == "0-100K") {
                $sqlString .= "and media.mediaSize < 102400 ";
            } else if ($size == "100K-1000K") {
                $sqlString .= "and media.mediaSize Between 102400 and 1024000 ";
            } else if ($size == ">1000K") {
                $sqlString .= "and media.mediaSize > 1024000 ";
            }
        }

        if ($sortby != "") {
            $sqlString .= "order by media.$sortby desc";
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

    public function getMyMedia($type, $sortby, $loggedInUserName)
    {
        $sqlString = "SELECT * FROM media where uploadedBy = '$loggedInUserName' and media.mediaType = '$type'";

        if ($sortby != "") {
            $sqlString .= "order by media.$sortby desc";
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

    public function getFavorite($type, $sortby, $loggedInUserName)
    {
        $sqlString = "SELECT media.* FROM media 
                                    inner join favorites on media.id=favorites.videoId 
                                    where favorites.userName='$loggedInUserName'
                                    and media.mediaType = '$type'";

        if ($sortby != "") {
            $sqlString .= "order by media.$sortby desc";
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

    public function getSharedMedia($type, $sortby, $loggedInUserName)
    {
        $sqlString = "SELECT media.* FROM media inner join contact on media.uploadedBy=contact.userName 
                                where contactUserName='$loggedInUserName' 
                                and media.mediaType = '$type'
                                and ((contactType='Family' and media.privacy='Family') 
                                     or (contactType='Friend' and media.privacy='Friend') 
                                     or (contactType='Fav' and media.privacy='Fav')) 
                                and status = 'Not Blocked'";

        if ($sortby != "") {
            $sqlString .= "order by media.$sortby desc";
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


}


?>