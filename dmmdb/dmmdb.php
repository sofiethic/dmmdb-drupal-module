<?php // defined('_JEXEC') or die('Restricted access'); ?>
<?php
function dmmdb_display_block($cid, $title, $url, $channels, $fullname, $nbmaxchannels, $width, $nbitems, $modal,  $comments, $search, $latest_videos, $most_viewed, $favorites, $categories, $same_author, $same_category, $same_keywords, $playlists, $friend_channels, $latest_on_friend_channels, $text_color,  $background_color, $tip_text_color, $tip_bg_color, $link_color) {
	
$theme_path = drupal_get_path('module', 'dmmdb');
	
$dmmdb_str = "\n<script type='text/javascript' src='/".$theme_path."/tmpl/jquery.min.js'></script>\n";
$dmmdb_str.= "<script type='text/javascript' src='/".$theme_path."/tmpl/jquery.colorbox.js'></script>\n";
$dmmdb_str.= "<link type='text/css' media='screen' rel='stylesheet' href='/".$theme_path."/tmpl/colorbox.css' />\n";
	
// defines where all Ajax requests should go
$dmmdb_str.= "<script type='text/javascript'>var orgsite='/".$theme_path."/tmpl/';</script>\n"; 

// get the component id
// $cid = JRequest::getvar('id',null,'get','int');
$cid = 463;

// $dmmdb_str.= "<br><br><br>";

if ( $modal == 1 )
{
$dmmdb_str.= "<div id=moloko2 style='display:none;padding:10px; background:#FFFFFF;'><div id=player_frame2 ></div></div>\n";

$dmmdb_str.= "<script type='text/javascript'>";
$dmmdb_str.= "\n var channels;\n";

$dmmdb_str.= "
      var colorbox=1;
      var winwidth  = $(window).width();
      var winheight = $(window).height();

      // 16:9 window
      if ((winwidth/winheight)>(4/3)) {
            height = winheight*0.75;
            width  = (height*4)/3;
       } else {
            width  = winwidth*0.75;
            height = (width*3)/4;;
       }
       pwidth=width;
";
}
else
{

$dmmdb_str.= "<script type='text/javascript'>";
$dmmdb_str.= "var channels;\n";

$dmmdb_str.= "
      var colorbox=0;
      pwidth=".$width.";
";
}
$dmmdb_str.= "</script>";

$dmmdb_str.= "<table width=".$width." border=0 cellpadding=0 cellspacing=0 align=center bgcolor='#".$background_color."'>";
$dmmdb_str.= "<tr><td width=100% align=right>";
$dmmdb_str.= "<div id=search_box></div>\n";
$dmmdb_str.= "</td></tr>";

$dmmdb_str.= "<script type='text/javascript'>";
$dmmdb_str.= "
       var nbitems=".$nbitems.";
       var actual=0;

        function loadsearch_box()
        {
            $('#search_box').html( getSearchBox( 'latest_videos', 'latest_on_friend_channels', '".$nbmaxchannels."', '".$cid."', '".$url."', '".$channels."', 1,
	                                           actual, nbitems, ".$width.", 
		        		           '#".$text_color."', '#".$background_color."', '#".$tip_bg_color."', '#".$link_color."', '#".$tip_text_color."' ) );
        }
</script>\n";

$dmmdb_str.= "<tr><td width=100% align=center>";
$dmmdb_str.= "<div id=player_frame1></div>\n";
$dmmdb_str.= "</td></tr>";
//$dmmdb_str.= "<tr><td>&nbsp;</td></tr>";
//$dmmdb_str.= "<tr><td>&nbsp;</td></tr>";

$dmmdb_str.= "
<script type='text/javascript'>
  var purl = '".$url."/modules/video/block/player.php?ID=-1&block_width='+pwidth+'&channel=".$channels."&align=left&cid=".$cid."&standalone=1';
  var furl = orgsite+'/proxy.php?proxy_url='+escape(purl);
  $.ajax({
      url: furl,\n";

if ( $modal == 1 )
{
$dmmdb_str.= "
      success: function(data){ $('#player_frame2').html( data );\n 
";
}
else
{
$dmmdb_str.= "
      success: function(data){ $('#player_frame1').html( data );\n 
";
}

if ( $comments == 1 )
{
$dmmdb_str.= "
      // player is loaded, we can load comments now 
      var purl = '".$url."/modules/video/block/video_comments.php?ajax=1&channel=".$channels."&module=video&block_width='+pwidth+'&align=left&actual=1&tip_bg=".$tip_bg_color."&tip_ln=".$link_color."&tip_tx=".$tip_text_color."&cid=".$cid."&standalone=1';
      var furl = orgsite+'/proxy.php?proxy_url='+escape(purl);
      $.ajax({
          url: furl,\n";

if ( $modal == 1 )
{
$dmmdb_str.= "
      success: function(data){ $('#player_frame2').html( $('#player_frame2').html()+data );}\n 
";
}
else
{
$dmmdb_str.= "
      success: function(data){ $('#video_comments1').html( data );}\n 
";
}

$dmmdb_str.= "
      }); 
";
}

$dmmdb_str.= "
      // getting the list of channels
      var purl = '".$url."/getchannels.php';
      var furl = orgsite+'/proxy.php?proxy_url='+escape(purl);
      var params = 'channels=".$channels."';
      $.post(furl, params,
          function(channels, status)
          { 
                    if ( status == 'success')
                    {    
                             // limit number of channels
                             if ( channels.counter > ".$nbmaxchannels." )
                             {
                                  channels.counter = ".$nbmaxchannels.";
                             }
                             for( ci=1; ci<=channels.counter; ci++)
                             {
                                      cid = ci*100+".$cid.";
                                      channel = channels[ci];
";

if ( $latest_videos == '1' )
{ 
$dmmdb_str.= "
                                       loadlatest_videos(channel,cid);\n";
}
if ( $most_viewed == '1' )
{ 
$dmmdb_str.= "
                                       loadmost_viewed(channel,cid);\n";
}
if ( $categories == '1' )
{ 
$dmmdb_str.= "
                                       loadcategories(channel,cid);\n";
}
if ( $favorites == '1' )
{ 
$dmmdb_str.= "
                                       loadfavorites(channel,cid);\n";
}
if ( $playlists == '1' )
{ 
$dmmdb_str.= "
                                       loadplaylists(channel,cid);\n";
}
if ( $friend_channels == '1' )
{ 
$dmmdb_str.= "
                                       loadfriend_channels(channel,cid);\n";
}
if ( $latest_on_friend_channels == '1' )
{ 
$dmmdb_str.= "
                                       loadlatest_on_friend_channels(channel,cid);\n";
}
$dmmdb_str.= "
                             }";

if ( $search == '1' )
{ 
$dmmdb_str.= "
                                       loadsearch_box();\n";
}

$dmmdb_str.= "
                    }
          }
          ,'json'); 
";

$dmmdb_str.="
       }
   }); 
</script>";    

   $dmmdb_str.= "<tr><td width=100% align=center>";
   $dmmdb_str.= "<div id=video_comments1></div>\n";
   $dmmdb_str.= "</td></tr>";
   $dmmdb_str.= "<tr><td>&nbsp;</td></tr>";
   $dmmdb_str.= "<tr><td>&nbsp;</td></tr>";

if ( $same_keywords == 1 )
{
   $dmmdb_str.= "<tr><td width=100% align=center>";
   $dmmdb_str.= "<div id=same_keywords></div>\n";
   $dmmdb_str.= "</td></tr>";
}

if ( $same_category == 1 )
{
   $dmmdb_str.= "<tr><td width=100% align=center>";
   $dmmdb_str.= "<div id=same_cat></div>\n";
   $dmmdb_str.= "</td></tr>";
}

if ( $same_author == 1 )
{
   $dmmdb_str.= "<tr><td width=100% align=center>";
   $dmmdb_str.= "<div id=same_author></div>\n";
   $dmmdb_str.= "</td></tr>";
}

for ( $di=1; $di<=$nbmaxchannels; $di++ )
{
   $ndi = $di*100+$cid;
   $dmmdb_str.= "<tr><td width=100% align=center id=latest_videos$ndi>";
   $dmmdb_str.= "</td></tr>";
   $dmmdb_str.= "<tr><td width=100% align=center id=most_viewed$ndi>";
   $dmmdb_str.= "</td></tr>";
   $dmmdb_str.= "<tr><td width=100% align=center id=favorites$ndi>";
   $dmmdb_str.= "</td></tr>";
   $dmmdb_str.= "<tr><td width=100% align=center id=categories$ndi>";
   $dmmdb_str.= "</td></tr>";
   $dmmdb_str.= "<tr><td width=100% align=center id=playlists$ndi>";
   $dmmdb_str.= "</td></tr>";
   $dmmdb_str.= "<tr><td width=100% align=center id=friend_channels$ndi>";
   $dmmdb_str.= "</td></tr>";
   $dmmdb_str.= "<tr><td width=100% align=center id=latest_on_friend_channels$ndi>";
   $dmmdb_str.= "</td></tr>";
}

   $dmmdb_str.= "
   <script type='text/javascript'>
     function loadlatest_videos(channel, cid)
     {
       var nbitems=".$nbitems.";
       var actual=0;
       var purl = '".$url."/getjson.php';
       var params = 'mode=latest_videos&channel='+channel+'&actual='+actual+'&nbitems='+nbitems+'&addparam=';
       var furl = orgsite+'/proxy.php?proxy_url='+escape(purl);

       $.post(furl, params, 
         function(items, status){
	    if ( status == 'success' )
	    {
               items.title = items.title.replace('this channel', 'channel '+channel);
               $('#latest_videos'+cid).html( buildDNavBar( 'latest_videos', 'latest_videos'+cid, '".$url."', channel, 1, cid,
	                                            items, actual, nbitems, ".$width.", '',
		        		            '#".$text_color."', '#".$background_color."', '#".$tip_bg_color."', '#".$link_color."', '#".$tip_text_color."' ) );
            }
	    else
	    {
	      alert( 'json error' );
	    }
         },'json'); 
     }
    </script>";    

   $dmmdb_str.= "
   <script type='text/javascript'>
     function loadmost_viewed(channel,cid)
     {
       var nbitems=".$nbitems.";
       var actual=0;
       var purl = '".$url."/getjson.php';
       var params = 'mode=most_viewed&channel='+channel+'&actual='+actual+'&nbitems='+nbitems+'&addparam=';
       var furl = orgsite+'/proxy.php?proxy_url='+escape(purl);
       $.post(furl, params, 
         function(items, status){
	    if ( status == 'success' )
	    {
               items.title = items.title.replace('this channel', 'channel '+channel);
               $('#most_viewed'+cid).html( buildDNavBar( 'most_viewed', 'most_viewed'+cid, '".$url."', channel, 1, cid,
	                                            items, actual, nbitems, ".$width.", '',
		        		            '#".$text_color."', '#".$background_color."', '#".$tip_bg_color."', '#".$link_color."', '#".$tip_text_color."' ) );
            }
	    else
	    {
	      alert( 'json error' );
	    }
         },'json'); 
     }
    </script>";    

   $dmmdb_str.= "
   <script type='text/javascript'>
     function loadcategories(channel,cid)
     {
       var nbitems=".$nbitems.";
       var actual=0;
       var purl = '".$url."/getjson.php';
       var params = 'mode=categories&channel='+channel+'&actual='+actual+'&nbitems='+nbitems+'&addparam=';
       var furl = orgsite+'/proxy.php?proxy_url='+escape(purl);
       $.post(furl, params, 
         function(items, status){
	    if ( status == 'success' )
	    {
               items.title = items.title+' on channel '+channel;
               $('#categories'+cid).html( buildDNavBar( 'categories', 'categories'+cid, '".$url."', channel, 1, cid,
	                                            items, actual, nbitems, ".$width.", '',
		        		            '#".$text_color."', '#".$background_color."', '#".$tip_bg_color."', '#".$link_color."', '#".$tip_text_color."' ) );
            }
	    else
	    {
	      alert( 'json error' );
	    }
         },'json'); 
     }
    </script>";    

   $dmmdb_str.= "
   <script type='text/javascript'>
     function loadfavorites(channel,cid)
     {
       var nbitems=".$nbitems.";
       var actual=0;
       var purl = '".$url."/getjson.php';
       var params = 'mode=favorites&channel='+channel+'&actual='+actual+'&nbitems='+nbitems+'&addparam=';
       var furl = orgsite+'/proxy.php?proxy_url='+escape(purl);
       $.post(furl, params, 
         function(items, status){
	    if ( status == 'success' )
	    {
               items.title = items.title+' on channel '+channel;
               $('#favorites'+cid).html( buildDNavBar( 'favorites', 'favorites'+cid, '".$url."', channel, 1, cid,
	                                            items, actual, nbitems, ".$width.", '',
		        		            '#".$text_color."', '#".$background_color."', '#".$tip_bg_color."', '#".$link_color."', '#".$tip_text_color."' ) );
            }
	    else
	    {
	      alert( 'json error' );
	    }
         },'json'); 
     }
    </script>";    

   $dmmdb_str.= "
   <script type='text/javascript'>
     function loadplaylists(channel,cid)
     {
       var nbitems=".$nbitems.";
       var actual=0;
       var purl = '".$url."/getjson.php';
       var params = 'mode=playlists&channel='+channel+'&actual='+actual+'&nbitems='+nbitems+'&addparam=';
       var furl = orgsite+'/proxy.php?proxy_url='+escape(purl);
       $.post(furl, params, 
         function(items, status){
	    if ( status == 'success' )
	    {
               items.title = items.title+' on channel '+channel;
               $('#playlists'+cid).html( buildDNavBar( 'playlists', 'playlists'+cid, '".$url."', channel, 1, cid,
	                                            items, actual, nbitems, ".$width.", '',
		        		            '#".$text_color."', '#".$background_color."', '#".$tip_bg_color."', '#".$link_color."', '#".$tip_text_color."' ) );
            }
	    else
	    {
	      alert( 'json error' );
	    }
         },'json'); 
     }
   </script>";    

   $dmmdb_str.= "
   <script type='text/javascript'>
     function loadfriend_channels(channel,cid)
     {
       var nbitems=".$nbitems.";
       var actual=0;
       var purl = '".$url."/getjson.php';
       var params = 'mode=friend_channels&channel='+channel+'&actual='+actual+'&nbitems='+nbitems+'&addparam=';
       var furl = orgsite+'/proxy.php?proxy_url='+escape(purl);
       $.post(furl, params, 
         function(items, status){
	    if ( status == 'success' )
	    {
               items.title = items.title+' ( channel '+channel+' )';
               $('#friend_channels'+cid).html( buildDNavBar( 'friend_channels', 'friend_channels'+cid, '".$url."', channel, 1, cid,
	                                            items, actual, nbitems, ".$width.", '',
		        		            '#".$text_color."', '#".$background_color."', '#".$tip_bg_color."', '#".$link_color."', '#".$tip_text_color."' ) );
            }
	    else
	    {
	      alert( 'json error' );
	    }
         },'json'); 
     }
    </script>";    

   $dmmdb_str.= "
   <script type='text/javascript'>
     function loadlatest_on_friend_channels(channel,cid)
     {
       var nbitems=".$nbitems.";
       var actual=0;
       var purl = '".$url."/getjson.php';
       var params = 'mode=latest_on_friend_channels&channel='+channel+'&actual='+actual+'&nbitems='+nbitems+'&addparam=';
       var furl = orgsite+'/proxy.php?proxy_url='+escape(purl);
       $.post(furl, params, 
         function(items, status){
	    if ( status == 'success' )
	    {
               items.title = items.title+' ( channel '+channel+' )';
               $('#latest_on_friend_channels'+cid).html( buildDNavBar( 'latest_on_friend_channels', 'latest_on_friend_channels'+cid, '".$url."', channel, 1, cid,
	                                            items, actual, nbitems, ".$width.", '',
		        		            '#".$text_color."', '#".$background_color."', '#".$tip_bg_color."', '#".$link_color."', '#".$tip_text_color."' ) );
            }
	    else
	    {
	      alert( 'json error' );
	    }
         },'json'); 
     }
    </script>";    


$dmmdb_str.= "</table>";

return $dmmdb_str;
} 
?>
