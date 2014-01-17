<?php
/*
Plugin Name:  Express Partage Facebook Button 
Plugin URI: http://www.share-express.com/fbexppar/
Description: Partage Facebook, personnalisé.
Version: 2.2.3
Author: Multi-Vision International 
Author URI: http://www.multi-vision.biz/
*/



if ( !defined('WP_CONTENT_URL') ) {
	define('FBEXPPAR_URL',get_option('siteurl').'/wp-content/plugins/'.plugin_basename(dirname(__FILE__)).'/');
} else {
	define('FBEXPPAR_URL',WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/');
}

register_activation_hook(__FILE__, array('fbexppar','plugin_activation'));
register_deactivation_hook(__FILE__, array('fbexppar','plugin_deactivation'));
register_uninstall_hook(__FILE__, array('fbexppar','plugin_uninstall'));

add_action('init', array('fbexppar', 'init'));



class fbexppar{
	
	const LANG_DIR = '/lang/'; // Defaut lang dirctory
	
	/**
	 * 
	 * The main 'loader'
	 */
	function init() {

		//Setup the translation
		load_plugin_textdomain('fbexppar',false, dirname(plugin_basename( __FILE__ ) ) . self::LANG_DIR);
		
    	// admin actions and hooks
        if (is_admin()) {
            self::admin_hooks();
        }
		add_filter('the_content', array('fbexppar','fb_share'));
    }
    
    /**
     * 
     * The admin hooks
     */
    public static function admin_hooks(){
		
    	add_action('admin_menu', array('fbexppar', 'admin_menu'));
		
    	
    	//Add schedules for wp_cron
    	add_filter('cron_schedules', array('fbexppar','custom_cron_schedules'));
    	
    	//Javascript
    	/* /
        if (isset($_GET['page']) && $_GET['page'] == 'fbexppar_settings') {
	    	wp_enqueue_script('jquery-ui-datepicker', plugins_url('/js/jquery.ui.datepicker.min.js',__FILE__), array('jquery', 'jquery-ui-core') );
			wp_enqueue_script('twitterfavs-admin-js', plugins_url('/js/admin.js',__FILE__), array('jquery-ui-datepicker') );
			
			//Smoothness style
			wp_enqueue_style('jquery.ui.smoothness', plugins_url('/css/smoothness/jquery-ui-1.8.17.custom.css',__FILE__));
		}
		/* */
    	
    }
    
    /**
     * 
     * Set up the admin menu(s)
     */
    public static function admin_menu(){
    	
		add_menu_page('Express Partage FB plugin admin page', "Express Partage FB", 8, basename(__FILE__),   array('fbexppar', "admin_settings"));
		add_submenu_page(basename(__FILE__), 'Parametrage', 'Parametrage', 8, basename(__FILE__), array('fbexppar', "admin_settings"));
		add_submenu_page(basename(__FILE__), 'statistiques', 'statistiques', 8, basename(__FILE__) . 'stats', array('fbexppar', 'admin_stat'));
    }
	
    /**
	*
	* Save option
	*/
	public static function admin_setting_update() {
	
		// nonce check
		if ( !wp_verify_nonce( $_POST['_wpnonce'], plugin_basename( __FILE__ ) ) ) return;
	
		$updated = false;
		if (isset($_POST['plugin_ok'])) {
			self::update_option('my_plugin_btname', $_POST['my_plugin_btname']);
			self::update_option('my_plugin_btposition', $_POST['my_plugin_btposition']);
			self::update_option('my_plugin_btpositiongd', $_POST['my_plugin_btpositiongd']);
			self::update_option('my_plugin_btorientation', $_POST['my_plugin_btorientation']);
			self::update_option('my_plugin_bttype', $_POST['my_plugin_bttype']);
			
			
			$updated = true;
		}
	
		if ($updated) {
			echo '<div id="message" class="updated fade">';
			echo '<p>'.__('Settings successfully updated.', 'fbexppar').'</p>';
			echo '</div>';
		} else {
			echo '<div id="message" class="error fade">';
			echo '<p>'.__('Unable to update settings.', 'fbexppar').'</p>';
			echo '</div>';
		}
	}
    /**
     * 
     * The admin settings page
     */
    public static function admin_settings(){
		?>

<div class="wrap">
  <div id="icon-options-general" class="icon32"></div>
  <h2>
    <?php _e('WordPress Express Partage FB plugin','fbexppar') ?>
  </h2>
  <?php if (isset($_POST['plugin_ok'])) {
			self::admin_setting_update(); // update setting
	} ?>
  <form action="" method="post">
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <th scope="row"> <label for="my_plugin_btname">
              <?php _e('Nom du bouton','fbexppar') ?>
            </label>
            </th>
          <td><input type="text" name="my_plugin_btname" id="my_plugin_btname" value="<?php esc_attr_e( self::get_option('my_plugin_btname' )); ?>" /></td>
        </tr>
        
        <tr valign="top">
          <th scope="row"> <label for="my_plugin_bttype">
              <?php _e('Type','fbexppar') ?>
            </label>
           
            </th>
          <td align="left">
          <table width="50%" align="left" background="0" cellpadding="0" cellspacing="0" >
          <tr>
          <td valign="bottom">
          <img src="<?php echo FBEXPPAR_URL;?>/images/stylec.png" ><br>
          <input type="radio" name="my_plugin_bttype" id="my_plugin_bttype" value="0" <?php if (self::get_option('my_plugin_bttype') == '0') echo 'checked="checked"'; ?> />
           <label for="fb_include_counter">Lien</label>
           </td>
           <td valign="bottom">
            <img src="<?php echo FBEXPPAR_URL;?>/images/stylel.png" ><br />
          <input type="radio" name="my_plugin_bttype" id="my_plugin_bttype" value="1" <?php if (self::get_option('my_plugin_bttype') == '1') echo 'checked="checked"'; ?> />
          <label for="fb_include_counter">Bouton</label><br />
            </td>
          </tr>
          </table>
         </td>
         </tr> 
        
        <tr valign="top">
          <th scope="row"> <label for="my_plugin_btposition">
              <?php _e('Orientation du text','fbexppar') ?>
            </label>
           
            </th>
          <td>
          
          <input type="radio" name="my_plugin_btorientation" id="my_plugin_btorientation" value="0" <?php if (self::get_option('my_plugin_btorientation') == '0') echo 'checked="checked"'; ?> />
           <label for="fb_include_counter">De gauche à droite (fr)</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           
          
          <input type="radio" name="my_plugin_btorientation" id="my_plugin_btorientation" value="1" <?php if (self::get_option('my_plugin_btorientation') == '1') echo 'checked="checked"'; ?> />
          <label for="fb_include_counter">De droite à gauche (ar)</label><br />
        
          
         </td>
         </tr> 
        
        <tr valign="top">
          <th scope="row"> <label for="my_plugin_btposition">
              <?php _e('Position du bouton','fbexppar') ?>
            </label>
           
            </th>
          <td>
          <input type="radio" name="my_plugin_btposition" id="my_plugin_btposition" value="0" <?php if (self::get_option('my_plugin_btposition') == '0') echo 'checked="checked"'; ?> />
          <label for="fb_include_counter">En haut</label><br />
          <input type="radio" name="my_plugin_btposition" id="my_plugin_btposition" value="1" <?php if (self::get_option('my_plugin_btposition') == '1') echo 'checked="checked"'; ?> />
          <label for="fb_include_counter">En bas</label><br />
             <input type="radio" name="my_plugin_btposition" id="my_plugin_btposition" value="2" <?php if (self::get_option('my_plugin_btposition') == '2') echo 'checked="checked"'; ?> />
          <label for="fb_include_counter">En haut et bas</label><br />
          <input type="radio" name="my_plugin_btposition" id="my_plugin_btposition" value="3" <?php if (self::get_option('my_plugin_btposition') == '3') echo 'checked="checked"'; ?> />
          <label for="fb_include_counter">Manuel : insérer [FBEXPPAR] à l’endroit voulu</label><br />
          <br />
          <input type="radio" name="my_plugin_btpositiongd" id="my_plugin_btpositiongd" value="0" <?php if (self::get_option('my_plugin_btpositiongd') == '0') echo 'checked="checked"'; ?> />
          <label for="fb_include_counter">A Droite</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input type="radio" name="my_plugin_btpositiongd" id="my_plugin_btpositiongd" value="1" <?php if (self::get_option('my_plugin_btpositiongd') == '1') echo 'checked="checked"'; ?> />
          <label for="fb_include_counter">A gauche</label><br />
          
          </td>
        </tr>
        
      </tbody>
    </table>
    <p class="submit">
      <input type="submit" class="button-primary" name="plugin_ok" value="<?php esc_attr_e('Save settings','fbexppar') ?>" />
    </p>
    <?php // Use nonce for verification
wp_nonce_field( plugin_basename( __FILE__ ), '_wpnonce' );?>
  </form>
</div>
<?php
		
    }
    
	/**
	 * 
	 * Get a plugin's specific option
	 * @param string $option_name
	 */
    public static function get_option($option_name){
    	return get_option('fbexppar_'.$option_name);
    }
    
    /**
     * 
     * Set a plugin's specific option
     * @param unknown_type $option_name
     */
	public static function update_option($option_name,$option_value){
    	return update_option('fbexppar_'.$option_name,$option_value);
    }
    
	/**
     * 
     * Delete a plugin's specific option
     * @param string $option_name
     */
    public static function delete_option($option_name){
    	return delete_option('fbexppar_'.$option_name);
    }
    
    /**
     * 
     * Usually, here, we set-up database tables or default options

     */
	public static function fb_share($content)
	{
		if (is_single())
		{
			wp_enqueue_style('my-style', plugins_url('style.css', __FILE__) );
			if (get_post_status($post->ID) == 'publish') {
				$url = get_permalink();
			}
			
			$nbpartage='';
			$share_count		=0;
			$like_count			=0;
			$comment_count		=0;
			$query_facebook="http://api.facebook.com/restserver.php?method=links.getStats&urls=".$url;
			if ($dom = DomDocument::load($query_facebook))
			{
				$liste_nb = $dom->getElementsByTagName('total_count');
				if ($liste_nb->length>0 )
				{
					$nbpartage=$liste_nb->item(0)->nodeValue;
				}
				
				$liste_nb = $dom->getElementsByTagName('share_count');
				if ($liste_nb->length>0 )
				{
					$share_count=intval($liste_nb->item(0)->nodeValue);
				}
				
				$liste_nb = $dom->getElementsByTagName('like_count');
				if ($liste_nb->length>0 )
				{
					$like_count=intval($liste_nb->item(0)->nodeValue);
				}
				
				$liste_nb = $dom->getElementsByTagName('comment_count');
				if ($liste_nb->length>0 )
				{
					$comment_count=intval($liste_nb->item(0)->nodeValue);
				}
				
				
				
			}
			

			
			
		 	$thumb="";
			$thumburl ="";
			$txtshare="";
			$titleshare="";
			 	
			/*$thepost = get_post(get_the_ID()); 
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
				$txtshare=urlencode($txtshare);
				
			}
			else 
			{	
				$txtshare=str_replace('[FBEXPPAR]', '', $thepost->post_excerpt);
				$txtshare= urlencode( $txtshare); 
			}
			$titleshare=$thepost->post_title;*/
			
			$btpositiongd="right";
					
			if  (self::get_option('my_plugin_btpositiongd' )==1) $btpositiongd="left";
			
		   
		   $tab_button=array(
		   'url'=>$url,
		   'btpositiongd'=>$btpositiongd,
		   'my_plugin_btorientation'=>self::get_option('my_plugin_btorientation'),
		   'my_plugin_bttype'=>self::get_option('my_plugin_bttype'),
		   'my_plugin_btname'=>self::get_option('my_plugin_btname' ),
		   'nbpartage'=>$nbpartage,
		   'path_plugin'=>FBEXPPAR_URL);
		   
		   
		   
		   
   		   $button=urlencode(base64_encode(serialize($tab_button)));
		 
           $button='<iframe src="'.plugins_url('ifbecppar.php', __FILE__).'?p='.get_the_ID().'&b='.$button.'" width="100" height="50" frameborder="0" scrolling="no" allowtransparency="true" style="float: '.$btpositiongd.';"></iframe>';
		  
		   $the_poat_id=get_the_ID();
		   if (self::get_option('my_plugin_btposition' )==0) 
		   		{
					self::save_FBEXPPAR_stat($the_poat_id,$url,$share_count,$like_count,$comment_count);
		   			return $button .'<br>'. str_replace('[FBEXPPAR]', '', $content) ;
				}
		   else 
				if (self::get_option('my_plugin_btposition' )==1) 
					{
						self::save_FBEXPPAR_stat($the_poat_id,$url,$share_count,$like_count,$comment_count);
						return  str_replace('[FBEXPPAR]', '', $content) .'<br>'. $button;
					}
				elseif (self::get_option('my_plugin_btposition' )==2) 
					{
						self::save_FBEXPPAR_stat($the_poat_id,$url,$share_count,$like_count,$comment_count);
						return $button .'<br>'. str_replace('[FBEXPPAR]', '', $content) .'<br>'. $button;
					}
				elseif (self::get_option('my_plugin_btposition' )==3) 
					{
						self::save_FBEXPPAR_stat($the_poat_id,$url,$share_count,$like_count,$comment_count);
						return str_replace('[FBEXPPAR]', $button, $content);
					}
				else  return  str_replace('[FBEXPPAR]', '', $content);
		}
		else  return  str_replace('[FBEXPPAR]', '', $content);
		
	} 
    public static function plugin_activation(){
    	//Do nice things
		global $wpdb;
		self::update_option('my_plugin_btname','Partager');
		self::update_option('my_plugin_btposition','0');
		self::update_option('my_plugin_btpositiongd', '0');
		self::update_option('my_plugin_btorientation', '0');
		self::update_option('my_plugin_bttype', '0');
		
		
		$table_name = $wpdb->prefix . "FBEXPPAR_stat";
		$sql = "CREATE TABLE $table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  post_id bigint(20) DEFAULT 0 NOT NULL,
		  url VARCHAR(400) DEFAULT '' NOT NULL,
		  shares int(11)  DEFAULT 0 NOT NULL,
		  likes int(11)  DEFAULT 0 NOT NULL,
		  comments int(11)  DEFAULT 0 NOT NULL,
		  total_count int(11)  DEFAULT 0 NOT NULL,
		  UNIQUE KEY id (id)
		   );";
	    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   		dbDelta( $sql );
    }
    
    /**
     * 
     * Purge cron and settings
     */
    public static function plugin_deactivation(){
    	//Do something (remove cron...)
    }
    
    /**
     * 
     * On plugin uninstallation
     */
    public static function plugin_uninstall(){
    	//May we remove plugin's options ...
    }
    
	/**
     * 
     * Add some new schedules
     * @param array $schedules
     */
    public static function custom_cron_schedules($schedules){
		//10 minutes, mainly for tests
		$schedules['10min'] = array(
			'interval'   => 60*10,// in seconds
			'display'   => __('Every 10 minutes','fbexppar'), 
		);
		
		return $schedules;
	}
	/***stat page ***/
	public static function admin_stat()
	{
	
		global $wpdb;

		$table = new Custom_Table_fbexppar_List_Table();
		$table->prepare_items();
		
		$message = '';
		if ('delete' === $table->current_action()) {
			$message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'custom_table_example'), count($_REQUEST['id'])) . '</p></div>';
		}
		?>
		<div class="wrap">
	
		<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
		
		<?php echo $message; ?>
	
		<form id="persons-table" method="GET">
        	<?php $table->search_box_date('Filtrer','Date post min:','Date post max'); ?>
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
			<?php $table->display() ?>
		</form>
	
		</div>
	<?php
	}


	 
	public static function save_FBEXPPAR_stat($post_id,$url,$shares,$likes,$comments)
	 {
		 global $wpdb;
		 $table_name = $wpdb->prefix . "FBEXPPAR_stat";
		 $myPost = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$table_name ." WHERE post_id = %d",$post_id), ARRAY_N);
		 if (is_null($myPost))
		 {
			 $wpdb->insert( 
				$table_name, 
				array( 
				'post_id' => $post_id, 
				'url' => $url ,
				'shares' => $shares,
				'likes' => $likes,
				'comments' => $comments,
				'total_count'=> $comments+$likes+$shares
				), 
				array( 
				'%d', 
				'%s' ,
				'%d', 
				'%d', 
				'%d', 
				'%d' 
				) 
		
			);
		}
		else
		{
			$wpdb->update( 
				$table_name, 
				array( 
					'shares' => $shares,
					'likes' => $likes,
					'comments' => $comments,
					'total_count'=> $comments+$likes+$shares
				), 
				array( 'post_id' => $post_id ), 
				array( 
					'%d',	
					'%d',	
					'%s',	
					'%d'	
				), 
				array( '%d' ) 
			);
		}
	}
	
}

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
class Custom_Table_fbexppar_List_Table extends WP_List_Table
{
    function __construct()
    {
        global $status, $page;

        parent::__construct();
    }

    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    function column_url($item)
    {
        return '<em><a href="'.$item['url'].'" target="_blank">' . $item['url'] . '</a></em>
		';
    }

    function column_post_id($item)
    {
        return sprintf('%d',$item['post_id']);
    }

    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

	

    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
			'post_id' => 'Post Id',
            'url' => 'Url',
            'shares' => 'Shares',
			'likes' => 'likes',
			'cts' => 'Comments',
			'total_count' => 'Total count',
			'post_date' => 'Date',
        );
        return $columns;
    }
    function get_sortable_columns()
    {
        $sortable_columns = array(
            'post_id' => array('post_id', false),
			'shares' => array('shares', true),
            'likes' => array('likes', true),
            'cts' => array('cts', true),
			'total_count' => array('total_count', true),
			'post_date' => array('post_date', true),
			
        );
        return $sortable_columns;
    }
    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }
    function process_bulk_action()
    {
        global $wpdb;
       
	    $table_name = $wpdb->prefix . 'FBEXPPAR_stat'; 

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
		
    }
	/**
	 * Display the search box.
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @param string $text The search button text
	 * @param string $input_id The search input id
	 */
	function search_box_date( $text, $input1,$input2 ) {

		$input_date1 = 's_date1';//$input1 . '-search-input';
		$input_date2 = 's_date2';//$input2 . '-search-input';

		if ( ! empty( $_REQUEST['orderby'] ) )
			echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
		if ( ! empty( $_REQUEST['order'] ) )
			echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
		if ( ! empty( $_REQUEST['post_mime_type'] ) )
			echo '<input type="hidden" name="post_mime_type" value="' . esc_attr( $_REQUEST['post_mime_type'] ) . '" />';
		if ( ! empty( $_REQUEST['detached'] ) )
			echo '<input type="hidden" name="detached" value="' . esc_attr( $_REQUEST['detached'] ) . '" />';
?>
<script>
moisX=["","Janvier","Fevrier","Mars","Avril","Mai","Juin","Juillet","Aout","Septembre","Octobre","Novembre","Decembre"];
JourM=["Di","Lu","Ma","Me","Je","Ve","Sa"];

var fermable_microcal=true;
var select_old= null;

var startWeek=0;//debut de la semaine 0=dim,1=lun,...
var jourPause={0:true,6:true}; //jour de pause de la semaine
var jourFeriee={"1-1":"jour an","1-5":"fête du travail","8-5":"armistice","14-7":"fête nationale","15-8":"ascencion","1-11":"armistice","11-11":"toussain","25-12":"noel"};

//structure la date
function strucDate(dateX)
{return {"pos":dateX.getDay(),"jour":dateX.getDate(),"mois":dateX.getMonth()+1,"annee":dateX.getFullYear()};}


var dateS= strucDate(new Date());//date Selectionné
var dnow= strucDate(new Date());//date actuelle


//retourne le ième jour du 1er du mois
function premJourMois(mois,annee)
{return (new Date(annee,mois-1,1).getDay());}
//retourne le jour max du mois
function JmaxMois(mois,annee)
{return (new Date(annee,mois,0).getDate());}


/* Test une date si elle est correct...spécial killer*/
function testTypeDate(dateEntree)
{
tst=false;
try
{rc=dateEntree.split("/");nd=new Date(rc[2],(rc[1]-1),rc[0]);
tst=(rc[2]>1800&&rc[2]<2200&&rc[2]==nd.getFullYear()&&rc[1]==(nd.getMonth()+1)&&rc[0]==nd.getDate());
} catch(e) {}
return tst;
}

//selection de la zone avec la souris
function choix(koi,code)
{
if (code)
{ select_old= koi.style.background;
   koi.style.background ='#c0c0FF';
}
else
{
koi.style.background =select_old;
}
}


function testTravail(oldX,xx,jj,mm,aa)
{
styleX="font-family:Tahoma;font-size:10px;text-align:center;";
styleX+=(oldX)?"":"color:#f5dd2a;";
styleX+="cursor:hand;border-right:1px #f5dd2a solid;border-bottom:1px #f5dd2a solid;";
if (jourPause[xx]||jourFeriee[jj+"-"+mm]!=null) styleX+="background:#188bf0;";
if (jj==dnow.jour&&mm==dnow.mois&&aa==dnow.annee) styleX+="border:1px red solid;";
return styleX;
}

//test si année bissextile
function bissextile(annee) {
return (annee%4==0 && annee %100!=0 || annee%400==0);
}

//Retourne le nombre de jour depuis le 1er janvier (num de semaine)
function nbJAnnee(dateX){
var nb_mois=[,0,31,59,90,120,151,181,212,243,273,304,334];
j=dateX.jour ; m=dateX.mois ; a=dateX.annee;
nb=nb_mois[m]+j-1 ;
if (bissextile(a) && m>2) nb++;
return nb;
}

//affiche le calendrier
function view_microcal(actif,ki,source,mxS,axS)
{
if (actif)
{
//decalage du mois su on clique sur -/+
if (mxS!=-1)
{
clearTimeout(cc);
document.getElementById(ki).focus();
fermable_microcal=true;
dateS.mois=mxS;
dateS.annee=axS;
if (dateS.mois<1) {dateS.annee--;dateS.mois+=12;}
if (dateS.mois>12) {dateS.annee++;dateS.mois-=12;}
}
//init
Dstart=(premJourMois(dateS.mois,dateS.annee)+7-startWeek)%7;
jmaxi=JmaxMois(dateS.mois,dateS.annee);
jmaxiAvant=JmaxMois((dateS.mois-1),dateS.annee);
//si on veux ajouter le numero de la semaine ...
//idxWeek=parseInt(nbJAnnee(strucDate(new Date(dateS.mois+'-01-'+dateS.annee)))/7,10)+1;

ymaxi=parseInt((jmaxi+Dstart+1)/7,10);

//generation du tableau
//--entête
htm="<table><tr style='font-size:10px;font-family:Tahoma;text-align:center;'>";
htm+="<td style='cursor:hand;' onclick=\"view_microcal(true,'"+ki+"','"+source+"',"+(dateS.mois-1)+","+dateS.annee+");\">-</td>";
htm+="<td colspan='5'> <b> "+moisX[dateS.mois]+"</b>&nbsp;"+dateS.annee+"</td>";
htm+="<td style='cursor:hand;' onclick=\"view_microcal(true,'"+ki+"','"+source+"',"+(dateS.mois+1)+","+dateS.annee+")\">+</td></tr>";
//--corps
htm+="<tr>";
//affichage des jours DLMMJVS
for (x=0;x<7;x++)
htm+="<td style='font-size:10px;font-family:Tahoma;'><b>"+JourM[(x+startWeek)%7]+"</b></td>";
htm+="</tr>"


//------------------------
for (y=0;y<=ymaxi;y++)
{
htm+="<tr>";
for (x=0;x<7;x++)
{
idxP=y*7+x-Dstart+1; //numero du jour
aa=dateS.annee;
xx=(x+startWeek)%7;
//jour du mois précedent
if (idxP<=0)
{
jj=idxP+jmaxiAvant;mm=dateS.mois-1;
if (mm==0)
{mm=12;aa--;}
htm+="<td style='"+testTravail(false,xx,jj,mm,aa)+"' onmouseover='choix(this,true)' onmouseout='choix(this,false)' onclick=\""+ki+".value='"+((jj<10)?"0":"")+jj+"-"+((mm<10)?"0":"")+mm+"-"+aa+"';"+ki+".style.color='black';\">"+jj+"</td>";
}
else if (idxP>jmaxi) //jour du mois suivant
{
jj=idxP-jmaxi;mm=dateS.mois+1;
if (mm==13)
{mm=1;aa++;}

htm+="<td style='"+testTravail(false,xx,jj,mm,aa)+"' onmouseover='choix(this,true)' onmouseout='choix(this,false)' onclick=\"document.getElementById('"+ki+"').value='"+((jj<10)?"0":"")+jj+"-"+((mm<10)?"0":"")+mm+"-"+aa+"';document.getElementById('"+ki+"').style.color='black';\">"+jj+"</td>";}
else //jour du mois en cours
{
jj=idxP;mm=dateS.mois;
htm+="<td style='"+testTravail(true,xx,jj,mm,aa)+"' onmouseover='choix(this,true)' onmouseout='choix(this,false)' onclick=\"document.getElementById('"+ki+"').value='"+((jj<10)?"0":"")+jj+"-"+((mm<10)?"0":"")+mm+"-"+aa+"';document.getElementById('"+ki+"').style.color='black';\">"+jj+"</td>";}
}
htm+="</tr>"
}//-------------------------
htm+="</table>"
//affiche le tableau
document.getElementById(source).innerHTML=htm;
document.getElementById(source).style.visibility="";
} else
{
//ferme le calendrier
if (fermable_microcal)
   cc=setTimeout("document.getElementById('"+source+"').style.visibility='hidden'",500);
}
}


function imprime_zone(titre, obj)

{
// Définie la zone à imprimer
var zi = document.getElementById(obj).innerHTML;

// Ouvre une nouvelle fenetre
var f = window.open("", "", "height=500, width=600,toolbar=0, menubar=0, scrollbars=1, resizable=1,status=0, location=0, left=10, top=10");

// Définit le Style de la page
f.document.body.style.color = '#000000';
f.document.body.style.backgroundColor = '#FFFFFF';
f.document.body.style.padding = "10px";

// Ajoute les Données
f.document.title = titre;
f.document.body.innerHTML += " " + zi + " ";

// Imprime et ferme la fenetre
f.window.print();
f.window.close();
return true;
}
</script>
<p class="search-box">
	<label class="screen-reader-text" for="<?php echo $input_date1 ?>"><?php echo $text; ?>:</label>
    <table align="center" width="50%"><tr><td>
    <div id="microcal1" name="microcal1" style="visibility:hidden;position:absolute;border:1px gray dashed;background:#ffffff;"></div>
	<?php echo $input1.": ";?><input type="search" id="<?php echo $input_date1 ?>" name="s_date1" value="<?php echo esc_attr( $_REQUEST['s_date1'] ); ?>" 
    onFocus="view_microcal(true,'<?php echo $input_date1 ?>','microcal1',-1,0);" onBlur="view_microcal(false,'<?php echo $input_date1 ?>','microcal1',-1,0);"  onkeyup="this.style.color=testTypeDate(this.value)?'black':'red'">
    <a  id="Calendd" href="#" title="Calendrier"> </a>
    </td>
    <td>
    
    <div id="microcal2" name="microcal2" style="visibility:hidden;position:absolute;border:1px gray dashed;background:#ffffff;"></div>
    <?php echo $input2.": ";?><input type="search" id="<?php echo $input_date2 ?>" name="s_date2" value="<?php echo esc_attr( $_REQUEST['s_date2'] ); ?>" 
    onFocus="view_microcal(true,'<?php echo $input_date2 ?>','microcal2',-1,0);" onBlur="view_microcal(false,'<?php echo $input_date2 ?>','microcal2',-1,0);"   onkeyup="this.style.color=testTypeDate(this.value)?'black':'red'">
    </td>
    <td>
	<?php submit_button( $text, 'button', false, false, array('id' => 'search-submit') ); ?>
    </td>
    </tr></table>
</p>

<?php
	}
	
    function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'FBEXPPAR_stat';
		$table_post = $wpdb->prefix . 'posts';
        $per_post = 10;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->process_bulk_action();


        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");


        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'a.total_count';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';
		$order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';
		
		$s_date1 = isset($_REQUEST['s_date1']) ? $_REQUEST['s_date1'] : "";
		$s_date2 = isset($_REQUEST['s_date2']) ? $_REQUEST['s_date2'] : "";
		$wheredate=" 1=1 ";
		$s_date1=preg_replace("/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/","\\3-\\2-\\1",str_replace('-','/',$s_date1));
		$s_date2=preg_replace("/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/","\\3-\\2-\\1",str_replace('-','/',$s_date2));
		if (trim($s_date1)!="")
		{
			$wheredate.= " and b.post_date>='".trim($s_date1)." 00:00:00'";
		}
		if (trim($s_date2)!="")
		{
			$wheredate.= " and b.post_date<'".trim($s_date2)." 23:59:59'";
		}
		


        $this->items = $wpdb->get_results($wpdb->prepare("SELECT a.id,
		a.post_id,
		a.url,
		a.shares,
		a.likes,
		a.comments as cts,
		a.total_count,
		b.post_date  
		FROM $table_name as a 
		inner join $table_post as b
		on (a.post_id=b.ID)
		where $wheredate 
		ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_post, $paged), ARRAY_A);


        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_post' => $per_post, 
            'total_pages' => ceil($total_items / $per_post)
        ));
    }
}