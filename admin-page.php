<?php
/* @var $this Permalinks */


?>
<div class="wrap">
    <div id="icon-link-manager" class="icon32"></div>
    <h2>Permanent Links</h2>
    <form method=”POST” action=”” >
    <table class=”form-table” >
    <tr valign=”top” >
    <th scope=”row” > < label for=”fname” > First Name </label> </th>
    <td> <input maxlength=”45” size=”25” name=”fname” /> </td>
    </tr>
    <tr valign=”top”>
    <th scope=”row”> <label for=”lname”> Last Name </label ></th>
    <td> <input id=”lname” maxlength=”45” size=”25” name=”lname” /></td>
    < /tr >
    <tr valign=”top”>
    <th scope=”row”><label for=”color”>Favorite Color</label ></th >
    <td>
    <select name=”color”>

    </td >
    </tr >
    FIGURE 4 - 16
    FIGURE 4 - 15
    Keeping It Consistent ❘ 93
    www
    
	
<?php
$qs=$this->is_permalinked() ? '?' : '&';
    add_thickbox(); 
$arr=$this->get_posts_links();
foreach ($arr as $key):
?>
    
    <a href="<?php echo get_permalink($key).$qs; ?>represent=1&TB_iframe=true&width=970&height=660" class="thickbox button-secondary">
        <?php echo $this->get_description_by_slug('') ?>
        </a>
<?php endforeach; ?>
    
   <?php var_dump($this->get_all_posts());
   ?>
    </div>
