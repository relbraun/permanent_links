<?php
/* @var $this Permalink */


?>
<div class="wrap">
    <div id="icon-link-manager" class="icon32"></div>
    <h2>Permanent Links</h2>

    
	
<?php
$qs=$this->is_permalinked() ? '?' : '&';
    add_thickbox(); 
$arr=$this->get_posts_links();
foreach ($arr as $key):
?>
    
    <a href="<?php echo get_permalink($key).$qs; ?>represent=1&TB_iframe=true&width=600&height=550" class="thickbox button-secondary">Press!</a>
<?php endforeach; ?>
    
   <?php var_dump($this->get_all_posts());
   ?>
    </div>
