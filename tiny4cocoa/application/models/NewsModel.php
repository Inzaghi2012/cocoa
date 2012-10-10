<?php
class NewsModel extends baseDbModel {
  
  public function news($page,$pageSize) {
    
    $start = ($page-1)*$pageSize;
    $sql = 
    "SELECT * FROM `cocoacms_news`
    ORDER BY `createdate` DESC 
    limit $start,$pageSize;";
    $result =  $this->fetchArray($sql);
    $ret = array();
    if(count($result)==0)
    return $ret;
    foreach($result as $item) {
		
      $item["createtime"] = $this->countTime($item["createdate"]);
      $ret[] = $item;
    } 
    return $ret;
  }
  
  public function oneNews($index) {
    
    $sql =
      "SELECT * FROM `cocoacms_news`
      WHERE `id` = $index;";
    $ret = $this->fetchArray($sql);
    $news = $ret[0];
    $news["createtime"] = $this->countTime($news["createdate"]);
    $news["content"] = $this->toHtml($news["content"]);
    return $news;
  }
  
  public function usernameById($id) {
    
    $user = $this->fetchArray("SELECT `username` FROM `cocoabbs_members` WHERE `uid`=$id");
    return $user[0]["username"];
  } 
  
  public function toHtml($content) {

    $content = str_replace("\r\n","<br/>",$content);
    $content = str_replace("\n","<br/>",$content);
    $content = str_replace("\r","<br/>",$content);
    return $content;
  }
  public function countTime($time)
  {
    $diff = time() - $time;
    if($diff<0) {
      $ret = "（时间不确）";
    }else if($diff<60) {
      $ret = $diff . "秒前";
    } else if($diff<3600) {
      $ret = floor($diff/60) . "分钟前";
    } else if($diff<3600*24) {
      $ret = floor($diff/3600) . "小时前";
    } else if($diff<3600*24*7) {
      $ret = floor($diff/3600/24) . "天前";
    } else if($diff<3600*24*30) {
      $ret = floor($diff/3600/24/7) . "周前";
    } else if($diff<3600*24*30*12) {
      $ret = floor($diff/3600/24/30) . "月前";
    } else {
      $ret = date("Y年m月d日",$time);
    }
    return $ret;
  }
    
}