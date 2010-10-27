<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php

echo "\n<script type='text/javascript' src='".JURI::root()."components".DS."com_dmmdb".DS."views".DS."dmmdb".DS."tmpl".DS."jquery.min.js'></script>\n";
echo "<script type='text/javascript' src='".JURI::root()."components".DS."com_dmmdb".DS."views".DS."dmmdb".DS."tmpl".DS."jquery.colorbox.js'></script>\n";
echo "<link type='text/css' media='screen' rel='stylesheet' href='".JURI::root()."/components/com_dmmdb/views/dmmdb/tmpl/colorbox.css' />\n";

// defines where all Ajax requests should go
echo "<script type='text/javascript'>var orgsite='/components/com_dmmdb/views/dmmdb/tmpl/';</script>\n"; 

// get the component id
$cid = JRequest::getvar('id',null,'get','int');

echo "<br><br><br>";

if ( $this->modal == 1 )
{
echo "<div id=moloko2 style='display:none;padding:10px; background:#FFFFFF;'><div id=player_frame2 ></div></div>\n";

echo "<script type='text/javascript'>";
echo "var channels;\n";

echo "
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

echo "<script type='text/javascript'>";
echo "var channels;\n";

echo "
      var colorbox=0;
      pwidth=".$this->width.";
";
}
echo "</script>";

echo "<table width=".$this->width." border=0 cellpadding=0 cellspacing=0 align=center bgcolor='#".$this->background_color."'>";
echo "<tr><td width=100% align=right>";
echo "<div id=search_box></div>\n";
echo "</td></tr>";

echo "<script type='text/javascript'>";
echo "
       var nbitems=".$this->nbitems.";
       var actual=0;

        function loadsearch_box()
        {
            $('#search_box').html( getSearchBox( 'latest_videos', 'latest_on_friend_channels', '".$this->nbmaxchannels."', '".$cid."', '".$this->url."', '".$this->channels."', 1,
	                                           actual, nbitems, ".$this->width.", 
		        		           '#".$this->text_color."', '#".$this->background_color."', '#".$this->tip_bg_color."', '#".$this->link_color."', '#".$this->tip_text_color."' ) );
        }
</script>\n";

echo "<tr><td width=100% align=center>";
echo "<div id=player_frame1></div>\n";
echo "</td></tr>";
echo "<tr><td>&nbsp;</td></tr>";
echo "<tr><td>&nbsp;</td></tr>";

echo "
<script type='text/javascript'>
  var purl = '".$this->url."/modules/video/block/player.php?ID=-1&block_width='+pwidth+'&channel=".$this->channels."&align=left&cid=".$cid."&standalone=1';
  var furl = orgsite+'/proxy.php?proxy_url='+escape(purl);
  $.ajax({
      url: furl,\n";

if ( $this->modal == 1 )
{
echo "
      success: function(data){ $('#player_frame2').html( data );\n 
";
}
else
{
echo "
      success: function(data){ $('#player_frame1').html( data );\n 
";
}

if ( $this->comments == 1 )
{
echo "
      // player is loaded, we can load comments now 
      var purl = '".$this->url."/modules/video/block/video_comments.php?ajax=1&channel=".$this->channels."&module=video&block_width='+pwidth+'&align=left&actual=1&tip_bg=".$this->tip_bg_color."&tip_ln=".$this->link_color."&tip_tx=".$this->tip_text_color."&cid=".$cid."&standalone=1';
      var furl = orgsite+'/proxy.php?proxy_url='+escape(purl);
      $.ajax({
          url: furl,\n";

if ( $this->modal == 1 )
{
echo "
      success: function(data){ $('#player_frame2').html( $('#player_frame2').html()+data );}\n 
";
}
else
{
echo "
      success: function(data){ $('#video_comments1').html( data );}\n 
";
}

echo "
      }); 
";
}

echo "
      // getting the list of channels
      var purl = '".$this->url."/getchannels.php';
      var furl = orgsite+'/proxy.php?proxy_url='+escape(purl);
      var params = 'channels=".$this->channels."';
      $.post(furl, params,
          function(channels, status)
          { 
                    if ( status == 'success')
                    {    
                             // limit number of channels
                             if ( channels.counter > ".$this->nbmaxchannels." )
                             {
                                  channels.counter = ".$this->nbmaxchannels.";
                             }
                             for( ci=1; ci<=channels.counter; ci++)
                             {
                                      cid = ci*100+".$cid.";
                                      channel = channels[ci];
";

if ( $this->latest_videos == '1' )
{ 
echo "
                                       loadlatest_videos(channel,cid);\n";
}
if ( $this->most_viewed == '1' )
{ 
echo "
                                       loadmost_viewed(channel,cid);\n";
}
if ( $this->categories == '1' )
{ 
echo "
                                       loadcategories(channel,cid);\n";
}
if ( $this->favorites == '1' )
{ 
echo "
                                       loadfavorites(channel,cid);\n";
}
if ( $this->playlists == '1' )
{ 
echo "
                                       loadplaylists(channel,cid);\n";
}
if ( $this->friend_channels == '1' )
{ 
echo "
                                       loadfriend_channels(channel,cid);\n";
}
if ( $this->latest_on_friend_channels == '1' )
{ 
echo "
                                       loadlatest_on_friend_channels(channel,cid);\n";
}
echo "
                             }";

if ( $this->search == '1' )
{ 
echo "
                                       loadsearch_box();\n";
}

echo "
                    }
          }
          ,'json'); 
";

echo "
       }
   }); 
</script>";    

   echo "<tr><td width=100% align=center>";
   echo "<div id=video_comments1></div>\n";
   echo "</td></tr>";
   echo "<tr><td>&nbsp;</td></tr>";
   echo "<tr><td>&nbsp;</td></tr>";

if ( $this->same_keywords == 1 )
{
   echo "<tr><td width=100% align=center>";
   echo "<div id=same_keywords></div>\n";
   echo "</td></tr>";
}

if ( $this->same_category == 1 )
{
   echo "<tr><td width=100% align=center>";
   echo "<div id=same_cat></div>\n";
   echo "</td></tr>";
}

if ( $this->same_author == 1 )
{
   echo "<tr><td width=100% align=center>";
   echo "<div id=same_author></div>\n";
   echo "</td></tr>";
}

for ( $di=1; $di<=$this->nbmaxchannels; $di++ )
{
   $ndi = $di*100+$cid;
   echo "<tr><td width=100% align=center id=latest_videos$ndi>";
   echo "</td></tr>";
   echo "<tr><td width=100% align=center id=most_viewed$ndi>";
   echo "</td></tr>";
   echo "<tr><td width=100% align=center id=favorites$ndi>";
   echo "</td></tr>";
   echo "<tr><td width=100% align=center id=categories$ndi>";
   echo "</td></tr>";
   echo "<tr><td width=100% align=center id=playlists$ndi>";
   echo "</td></tr>";
   echo "<tr><td width=100% align=center id=friend_channels$ndi>";
   echo "</td></tr>";
   echo "<tr><td width=100% align=center id=latest_on_friend_channels$ndi>";
   echo "</td></tr>";
}

   echo "
   <script type='text/javascript'>
     function loadlatest_videos(channel, cid)
     {
       var nbitems=".$this->nbitems.";
       var actual=0;
       var purl = '".$this->url."/getjson.php';
       var params = 'mode=latest_videos&channel='+channel+'&actual='+actual+'&nbitems='+nbitems+'&addparam=';
       var furl = orgsite+'/proxy.php?proxy_url='+escape(purl);

       $.post(furl, params, 
         function(items, status){
	    if ( status == 'success' )
	    {
               items.title = items.title.replace('this channel', 'channel '+channel);
               $('#latest_videos'+cid).html( buildDNavBar( 'latest_videos', 'latest_videos'+cid, '".$this->url."', channel, 1, cid,
	                                            items, actual, nbitems, ".$this->width.", '',
		        		            '#".$this->text_color."', '#".$this->background_color."', '#".$this->tip_bg_color."', '#".$this->link_color."', '#".$this->tip_text_color."' ) );
            }
	    else
	    {
	      alert( 'json error' );
	    }
         },'json'); 
     }
    </script>";    

   echo "
   <script type='text/javascript'>
     function loadmost_viewed(channel,cid)
     {
       var nbitems=".$this->nbitems.";
       var actual=0;
       var purl = '".$this->url."/getjson.php';
       var params = 'mode=most_viewed&channel='+channel+'&actual='+actual+'&nbitems='+nbitems+'&addparam=';
       var furl = orgsite+'/proxy.php?proxy_url='+escape(purl);
       $.post(furl, params, 
         function(items, status){
	    if ( status == 'success' )
	    {
               items.title = items.title.replace('this channel', 'channel '+channel);
               $('#most_viewed'+cid).html( buildDNavBar( 'most_viewed', 'most_viewed'+cid, '".$this->url."', channel, 1, cid,
	                                            items, actual, nbitems, ".$this->width.", '',
		        		            '#".$this->text_color."', '#".$this->background_color."', '#".$this->tip_bg_color."', '#".$this->link_color."', '#".$this->tip_text_color."' ) );
            }
	    else
	    {
	      alert( 'json error' );
	    }
         },'json'); 
     }
    </script>";    

   echo "
   <script type='text/javascript'>
     function loadcategories(channel,cid)
     {
       var nbitems=".$this->nbitems.";
       var actual=0;
       var purl = '".$this->url."/getjson.php';
       var params = 'mode=categories&channel='+channel+'&actual='+actual+'&nbitems='+nbitems+'&addparam=';
       var furl = orgsite+'/proxy.php?proxy_url='+escape(purl);
       $.post(furl, params, 
         function(items, status){
	    if ( status == 'success' )
	    {
               items.title = items.title+' on channel '+channel;
               $('#categories'+cid).html( buildDNavBar( 'categories', 'categories'+cid, '".$this->url."', channel, 1, cid,
	                                            items, actual, nbitems, ".$this->width.", '',
		        		            '#".$this->text_color."', '#".$this->background_color."', '#".$this->tip_bg_color."', '#".$this->link_color."', '#".$this->tip_text_color."' ) );
            }
	    else
	    {
	      alert( 'json error' );
	    }
         },'json'); 
     }
    </script>";    

   echo "
   <script type='text/javascript'>
     function loadfavorites(channel,cid)
     {
       var nbitems=".$this->nbitems.";
       var actual=0;
       var purl = '".$this->url."/getjson.php';
       var params = 'mode=favorites&channel='+channel+'&actual='+actual+'&nbitems='+nbitems+'&addparam=';
       var furl = orgsite+'/proxy.php?proxy_url='+escape(purl);
       $.post(furl, params, 
         function(items, status){
	    if ( status == 'success' )
	    {
               items.title = items.title+' on channel '+channel;
               $('#favorites'+cid).html( buildDNavBar( 'favorites', 'favorites'+cid, '".$this->url."', channel, 1, cid,
	                                            items, actual, nbitems, ".$this->width.", '',
		        		            '#".$this->text_color."', '#".$this->background_color."', '#".$this->tip_bg_color."', '#".$this->link_color."', '#".$this->tip_text_color."' ) );
            }
	    else
	    {
	      alert( 'json error' );
	    }
         },'json'); 
     }
    </script>";    

   echo "
   <script type='text/javascript'>
     function loadplaylists(channel,cid)
     {
       var nbitems=".$this->nbitems.";
       var actual=0;
       var purl = '".$this->url."/getjson.php';
       var params = 'mode=playlists&channel='+channel+'&actual='+actual+'&nbitems='+nbitems+'&addparam=';
       var furl = orgsite+'/proxy.php?proxy_url='+escape(purl);
       $.post(furl, params, 
         function(items, status){
	    if ( status == 'success' )
	    {
               items.title = items.title+' on channel '+channel;
               $('#playlists'+cid).html( buildDNavBar( 'playlists', 'playlists'+cid, '".$this->url."', channel, 1, cid,
	                                            items, actual, nbitems, ".$this->width.", '',
		        		            '#".$this->text_color."', '#".$this->background_color."', '#".$this->tip_bg_color."', '#".$this->link_color."', '#".$this->tip_text_color."' ) );
            }
	    else
	    {
	      alert( 'json error' );
	    }
         },'json'); 
     }
   </script>";    

   echo "
   <script type='text/javascript'>
     function loadfriend_channels(channel,cid)
     {
       var nbitems=".$this->nbitems.";
       var actual=0;
       var purl = '".$this->url."/getjson.php';
       var params = 'mode=friend_channels&channel='+channel+'&actual='+actual+'&nbitems='+nbitems+'&addparam=';
       var furl = orgsite+'/proxy.php?proxy_url='+escape(purl);
       $.post(furl, params, 
         function(items, status){
	    if ( status == 'success' )
	    {
               items.title = items.title+' ( channel '+channel+' )';
               $('#friend_channels'+cid).html( buildDNavBar( 'friend_channels', 'friend_channels'+cid, '".$this->url."', channel, 1, cid,
	                                            items, actual, nbitems, ".$this->width.", '',
		        		            '#".$this->text_color."', '#".$this->background_color."', '#".$this->tip_bg_color."', '#".$this->link_color."', '#".$this->tip_text_color."' ) );
            }
	    else
	    {
	      alert( 'json error' );
	    }
         },'json'); 
     }
    </script>";    

   echo "
   <script type='text/javascript'>
     function loadlatest_on_friend_channels(channel,cid)
     {
       var nbitems=".$this->nbitems.";
       var actual=0;
       var purl = '".$this->url."/getjson.php';
       var params = 'mode=latest_on_friend_channels&channel='+channel+'&actual='+actual+'&nbitems='+nbitems+'&addparam=';
       var furl = orgsite+'/proxy.php?proxy_url='+escape(purl);
       $.post(furl, params, 
         function(items, status){
	    if ( status == 'success' )
	    {
               items.title = items.title+' ( channel '+channel+' )';
               $('#latest_on_friend_channels'+cid).html( buildDNavBar( 'latest_on_friend_channels', 'latest_on_friend_channels'+cid, '".$this->url."', channel, 1, cid,
	                                            items, actual, nbitems, ".$this->width.", '',
		        		            '#".$this->text_color."', '#".$this->background_color."', '#".$this->tip_bg_color."', '#".$this->link_color."', '#".$this->tip_text_color."' ) );
            }
	    else
	    {
	      alert( 'json error' );
	    }
         },'json'); 
     }
    </script>";    


echo "</table>";
?>
