<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Permalinks
 *
 * @author Kalman
 */
class Permalinks
{
    public $links=array();
    
    protected $all_posts;
    
    protected $query_string='&';
    
    public function __construct()
    {
        $this->init();
    }
    
    public function addPermalink($repma)
    {
        if(is_array($repma) && isset($repma['slug']))
        {
            $this->links[]=array('slug'=>$repma['slug'],
                                'description' => isset($repma['description']) ? $repma['description']: $repma['slug']);
        }
        
    }

    /**
     * @param string $slug
     * @return string|bool string if the slug exists with its description, false if not.
     */
    public function get_description_by_slug($slug)
    {
        foreach($this->links as $link){
            if($link['slug']==$slug)
                return $link['description'];
        }
        return $slug;
    }
    
    public function get_link_by_slug($slug)
    {
        return new Link($slug);
    }
    
    /**
     * 
     * @global wpdb $wpdb
     * @param string $template_page
     * @return int|null int if post exists or null if not
     */
    protected function get_post_by_template($template_page)
    {
        global $wpdb;
        
        $sql="SELECT post_id FROM {$wpdb->prefix}postmeta 
            WHERE meta_key='_wp_page_template' AND meta_value='{$template_page}'";
        $posts=$wpdb->get_col($sql);
        if(!empty($posts))
            return $posts[0];
        else
            return null;
    }
    
    protected function get_post_by_slug($slug)
    {
        return get_option('permanent-link_'.$slug);
    }
    /**
     * 
     * @return array A slug => file_path to all templates containing the awesome function wp_permanent_link. 
     */
    protected function get_templates_with_code()
    {
        $templates=array();
        $files=  array_values(get_page_templates());
        foreach($files as $file){
            $content=  file_get_contents(STYLESHEETPATH.DIRECTORY_SEPARATOR.$file);
            $strstr=strstr($content, 'wp_permanent_link');
            
            if($strstr){
                preg_match_all("/\(\s*['\"](\w+)['\"]\s*,/", $strstr, $slugs);
                foreach ($slugs[1] as $slug)
                {
                    $templates[$slug]=$file;
                }   
            }
        }
        
        return $templates;
    }
    
    protected function get_files_with_code()
    {
        $templates=array();
        $files=  array_values(wp_get_theme()->get_files(null, -1));
        foreach($files as $file){
            $content=  file_get_contents($file);
            $strstr=strstr($content, 'wp_permanent_link');
            if($strstr){
                preg_match_all("/\(\s*['\"](\w+)['\"]\s*,/", $strstr, $slugs);
                foreach ($slugs[1] as $slug)
                {
                    $templates[$slug]=$file;
                }   
            }
        }
        
        return $templates;
    }
    
    public function get_posts_links()
    {
        $arr;
        $temps=$this->get_templates_with_code();
        foreach ($temps as $slug => $file){
            $arr[$file]=$this->get_post_by_template($file);
        }
        return $arr;
    }
    
    public function link_page()
    {   
        include 'admin-page.php';
    }
    /**
     * 
     * @global WP_Rewrite $wp_rewrite
     */
    protected function is_permalinked()
    {
        global $wp_rewrite;
        
        return strlen($wp_rewrite->permalink_structure);
    }
    /**
     * 
     * @global wpdb $wpdb
     * @return array
     */
    protected function get_all_posts()
    {
        global $wpdb;
        $sql="SELECT ID, post_title, post_type FROM {$wpdb->prefix}posts 
        WHERE post_status='publish' ORDER BY post_type";
           
        $posts=  $wpdb->get_results($sql);
        return $posts;
    }
    
    protected function renderAllPosts($post_id)
    {
        foreach($this->all_posts as $post){
            $selected=selected($post_id, $post->ID,false);
            echo "<option value='{$post->ID}' {$selected}>{$post->post_title}</option>\n";
        }
    }
    
    protected function init()
    {
        if($this->is_permalinked())
            $this->query_string='?';
        $this->all_posts=$this->get_all_posts();
        add_action('admin_menu', array(&$this, 'register_admin_menu'));
        add_action('admin_enqueue_scripts', array(&$this, 'admin_script'));
        add_action('wp_enqueue_scripts', array(&$this, 'enqueue_style'));
        add_action('wp_ajax_save_permanent_link', array(&$this, 'save'));
    }
    /*******************************************************************
     *                          HOOKS                                  *
     *******************************************************************/
    
    public function register_admin_menu()
    {
        add_theme_page( 'permanent-links', 'Permanent Links', 'edit_theme_options', __FILE__, array(&$this, 'link_page') );
    }
    
    public function admin_script($hook)
    {
        wp_enqueue_script( 'permalinks_script', plugin_dir_url(__FILE__).'/js/script.js', array('jquery', 'thickbox') );
        wp_enqueue_style('thickbox');
    }
    
    public function enqueue_style()
    {
        wp_enqueue_style('enqueue_permanent_styel', plugin_dir_url(__FILE__).'/css/style.css');
    }
    
    public function save()
    {
        $link=new Link($_POST['slug']);
        $link->post_id=$_POST['post'];
        $link->save();
        die;
    }
   
}


class Link
{
    public $post_id;
    
    public $slug;

    protected $description;
    
    protected $displaing=false;
    /**
     * 
     * @global Permalinks $PL
     * @param string $slug
     * @param int $post_id
     */
    public function __construct($slug, $post_id=null)
    {
        global $PL;
        $this->slug=$slug;
        $this->description=$PL->get_description_by_slug($slug);
       $opt =  get_option('permanent-link_'.$slug);
       if($opt !== false)
           $this->post_id=$opt;
       if($post_id!==null)
           $this->post_id=$post_id;
       if(isset($_GET['represent']))
           $this->displaing=$_GET['represent'];
    }

    public function __get($val)
    {
        $met='get'.$val;
        if(method_exists($this, $met))
            return $this->$met();
    }
    
    public function render($text, $class='')
    {
        $display='';
        if($this->displaing && $this->displaing==$this->slug)
            $display=' perma-thickbox';
        echo "<a href='".get_permalink($this->post_id)."' class='{$class}{$display}'>{$text}</a>";
    }
    
    public function save()
    {
        update_option('permanent-link_'.$this->slug, $this->post_id);
    }

    public function getDescription()
    {
        return $this->description;
    }
    
    protected function enqueue_style()
    {
        wp_enqueue_style('enqueue_permanent_styel', plugin_dir_url(__FILE__).'/css/style.css');
    }
}
?>
