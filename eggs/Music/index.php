<?php

  // error_reporting(E_ALL & ~E_NOTICE);
  require_once('./playlist.php');
  require_once('./libs/Smarty.class.php');
  $smarty = new Smarty();
  $smarty->left_delimiter = "{";            //左定界符
  $smarty->right_delimiter= "}";            //右定界符
  $smarty->template_dir   = "tpl";          //html模板地址
  $smarty->compile_dir    = "template_c";   //编译生成的文件
  $smarty->cache_dir      = "cache";        //缓存
  // $smarty->caching        = true;           //开启缓存
  // $smarty->cache_lifetime = 120;            //缓存时间
  $url1 = isset($_REQUEST['url1'])?$_REQUEST['url1']:"http://music.163.com/#/playlist?id=8009";
  $url2 = isset($_REQUEST['url2'])?$_REQUEST['url2']:"http://music.163.com/#/playlist?id=3248614";
  $songList = new PlayList();
  $listUrl1 = $songList->Analysis($url1);
  $listUrl2 = $songList->Analysis($url2);
  $url1Compare = $listUrl1;
  $url2Compare = $listUrl2;
  $url1Num  = count($listUrl1)-4;
  $url2Num  = count($listUrl2)-4;
  $url1Per  = $url1Num >= $url2Num ? "100%" : round(($url1Num/$url2Num)*100,2)."%";
  $url2Per  = $url2Num >= $url1Num ? "100%" : round(($url2Num/$url1Num)*100,2)."%";
  if ($url1Compare['userid'] = $url2Compare['userid']){
      for ($i=0; $i < 4; $i++) {
           array_pop($url1Compare);
           array_pop($url2Compare);
      }
      $intersect= array_intersect_assoc($url1Compare, $url2Compare);
  } else{
      $intersect= array_intersect_assoc($listUrl1, $listUrl2);   //共同喜欢的音乐
  }

  $similar  = (count($intersect)*2)/($url1Num+$url2Num);
  $similarPer = $similar*100;
  $simiPro  = round($similarPer)."%";      //进度条css用
  $simiDis  = round($similarPer,2)."%";    //进度条显示用
  $url1DisplayInfo = $songList->similarDisplay($url1Per);
  $url2DisplayInfo = $songList->similarDisplay($url2Per);
  $unionDisplayInfo= $songList->similarDisplay($simiDis);
  if ($listUrl1['playlistname'] == "网易云音乐<") {
      $listUrl1['playlistname'] = "该歌单不存在";
      $listUrl1['username']     = "";
  }
  if ($listUrl2['playlistname'] == "网易云音乐<") {
      $listUrl2['playlistname'] = "该歌单不存在";
      $listUrl2['username']     = "";
  }
  $smarty->assign('listUrl1',$listUrl1);
  $smarty->assign('listUrl2',$listUrl2);
  $smarty->assign('url1Num',$url1Num);
  $smarty->assign('url2Num',$url2Num);
  $smarty->assign('url1Per',$url1Per);
  $smarty->assign('url2Per',$url2Per);
  $smarty->assign('intersectNum',count($intersect));
  $smarty->assign('url1DisplayInfo',$url1DisplayInfo);
  $smarty->assign('url2DisplayInfo',$url2DisplayInfo);
  $smarty->assign('unionDisplayInfo',$unionDisplayInfo);
  $smarty->assign('simiPro',$simiPro);
  $smarty->assign('simiDis',$simiDis);
  $smarty->assign('simiDis',$simiDis);
  $smarty->assign('intersect',$intersect);
  $smarty->display('tpl.html');

 ?>
