<?php
define( 'SHORTINIT', true );
$location = $_SERVER['DOCUMENT_ROOT'];
include ($location . '/wp-load.php');

include( $location  . '/wp-includes/formatting.php');
include($location  . '/wp-includes/l10n.php');
include( $location  . '/wp-includes/class-wp-walker.php');
include($location  . '/wp-includes/post.php');
include($location  . '/wp-includes/taxonomy.php');
 
include($location  . '/wp-includes/category-template.php');
include( $location  . '/wp-includes/post-template.php');
include( $location  . '/wp-includes/post-thumbnail-template.php'); 
include( $location  . '/wp-includes/meta.php');
include( $location  . '/wp-includes/link-template.php');
include( $location  . '/wp-includes/category.php');

$the_ID=$_GET['p'];

		 	$thumb="";
			$thumburl ="";
			$txtshare="";
			$titleshare="";
			 	
			
			$thepost = get_post($the_ID); 
			if ( has_post_thumbnail() ) 
			{
				$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($thepost->ID), 'full' ); 
				$thumburl = $thumb['0'];
			}
			else
			{
				$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $thepost->post_content, $matches);
			  	$thumburl = $matches [1] [0];
			}
			
			if (trim($thepost->post_excerpt)=="")
			{
				$txtshare=$thepost->post_content;
				$pieces = explode("<!--more-->",$txtshare);
  			 	$txtshare=$pieces[0];
				$txtshare=trim(strip_tags($txtshare));
				
				$txtshare=str_replace('[FBEXPPAR]', '', $txtshare);
				$txtshare=$txtshare;
				
			}
			else 
			{	
				$txtshare=str_replace('[FBEXPPAR]', '', $thepost->post_excerpt);
				$txtshare=  $txtshare; 
			}
			$titleshare=$thepost->post_title;
			$btpositiongd="right";
			
			
			
			
		   
		   $tab_button2=array(
		  
		   'image'=>$thumburl,
		   'title'=>$titleshare,
		   'summary'=>$txtshare);



$tab_button=$_GET['b'];

$tab_button=unserialize(base64_decode(urldecode($tab_button)));
$tab_button=array_merge($tab_button,$tab_button2)
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>

<title>Partage FaceBook</title>

<style> 



/* common */

	* { margin: 0; padding: 0; }

	

	body {

		font: normal 14px Arial, sans-serif;

		line-height: 15px;

    	color: #000

	}



	a {

		text-decoration:none; 

	}



	.fb_partage_petitel {

		background-color: transparent;

	}



	.fb_partage_petitel .fb_partagebouton { 

		display: block;

		padding: 0px 0px 0px 3px;

		width: 58px;

		height: 16px; 

		color: #3B5998;

		font-size:11px;

		font-family:"lucida grande",tahoma,verdana,arial,sans-serif;

		text-align:left;

		text-align:<?php echo (($tab_button['my_plugin_btorientation'] ==1)?'right':'left');?>;

		border:1px solid #d8dfea;

		background:url('<?php echo $tab_button['path_plugin'];?>/images/fb.png') no-repeat top <?php echo (($tab_button['my_plugin_btorientation'] ==1)?'left':'right');?>;

		background-color: #fff;

	}



	.fb_partage_petitel a:hover { 

		color:#fff;

		background-color: #3b5998;

		border-color: #295582;

	}

	

	







	.fb_partage_petitel a:link { 

		color:#fff;

		background-color: #3b5998;

		border-color: #295582;

		

	}

	

	.fb_partage_petitel a:visited { 

		color:#fff;

		background-color: #3b5998;

		border-color: #295582;

		

	}

	

	.fb_partage_petitel a:active { 

		color:#fff;

		background-color: #3b5998;

		

	}



	.fb_partage_petitel .fb_sharecount {

		background-color: #fff;

		border:1px solid #C1C1C1;

		font-size: 11px;

		height: 16px; 

		margin-right: 3px;

		color: #000;

		text-align:center;

	}

	

	.fb_partage_petitel .fb_sharecountgrande {

		background-color: #fff;

		border:1px solid #C1C1C1;

		font-size: 14px;

		height: 24px; 

		margin-right: 3px;

		color: #000;

		text-align:center;

	}







</style>



    <script>

	function share() {

	var linkshare="http://www.facebook.com/sharer.php?s=100&p[url]=<?php echo urlencode($tab_button['url']);?>&p[images][0]=<?php echo urlencode($tab_button['image']);?>&p[title]=<?php echo urlencode($tab_button['title']);?>&p[summary]=<?php echo urlencode($tab_button['summary']);?>" ;

	window.open(linkshare,'sharer','toolbar=0,status=0,width=626,height=436');



	return false;			

	}

	</script>

			

</head>

<body>

<div class="fb_partage_petitel">

	<table background="0" cellpadding="0" cellspacing="0" >

    

    <?php	

			if ($tab_button['nbpartage']>=1000000) $tab_button['nbpartage']=($tab_button['nbpartage']/1000000)."m";

			if ($tab_button['nbpartage']>=1000) $tab_button['nbpartage']=($tab_button['nbpartage']/1000)."k";

			

			if ($tab_button['my_plugin_bttype']==0)

			{

				if ($tab_button['my_plugin_btorientation']==0)

				{?>

					<td>

					<a  href="http://www.facebook.com/sharer.php?u=<?php echo $tab_button['url'];?>" 

					title="Partager sur Facebook" 

					class="fb_partagebouton" 

					onclick="return share();" 

					target="_blank"><?php echo $tab_button['my_plugin_btname'];?>

					</a></td>

					<td width="1" ><td>

					<?php

					if (($tab_button['nbpartage']!='') && (intval($tab_button['nbpartage'])!=0) )

					   {?>

						<td class="fb_sharecount">&nbsp;<?php echo $tab_button['nbpartage'];?>&nbsp;</td>

						

						<?php		   

						}

				}

				else

				{

					if (($tab_button['nbpartage']!='') && (intval($tab_button['nbpartage'])!=0) )

					   {?>

						<td class="fb_sharecount">&nbsp;<?php echo $tab_button['nbpartage'];?>&nbsp;</td>

						

						<?php		   

						}?>

				

					<td>

					<a  href="http://www.facebook.com/sharer.php?u=<?php echo $tab_button['url'];?>" 

					title="Partager sur Facebook" 

					class="fb_partagebouton" 

					onclick="return share();" 

					target="_blank"><?php echo $tab_button['my_plugin_btname'];?>

					</a></td>

					

				<?php

				}		

				

			}

			else

			{

				if (($tab_button['nbpartage']!='') && (intval($tab_button['nbpartage'])!=0) )

				{?>

				<tr>

				

				<td class="fb_sharecountgrande">&nbsp;<?php echo $tab_button['nbpartage'];?>&nbsp;</td>

				</tr>   

				<tr><td height="1"></td></tr>

				<?php

				}

	

				?>

				<tr>

					<td>

					<a  href="http://www.facebook.com/sharer.php?u=<?php echo $tab_button['url'];?>" 

					title="Partager sur Facebook" 

					class="fb_partagebouton" 

					onclick="return share();" 

					target="_blank"><?php echo $tab_button['my_plugin_btname'];?>

					</a></td>

			<?php

			}

			?>

						

						

			</tr></table>

			 

		   </div>

</body>

</html>