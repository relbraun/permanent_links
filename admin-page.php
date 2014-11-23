<?php
/* @var $this Permalinks */


?>
<div class="wrap">
    <div id="icon-link-manager" class="icon32"></div>
    <h2>Permanent Links</h2>
    <form method=”POST” action=”” >
    <table class=”form-table” >
        <tr valign=”top” >
            <th scope=”row” ><label for=”fname”>First Name</label></th>
            <td><input maxlength=”45” size=”25” name=”fname” /></td>
        </tr>
        <tr valign=”top”>
            <th scope=”row”> <label for=”lname”> Last Name </label ></th>
            <td><input id=”lname” maxlength=”45” size=”25” name=”lname” /></td>
        </tr>
        <tr valign=”top”>
            <th scope=”row”><label for=”color”>Favorite Color</label></th>
            <td>
                <select name=”color”>

                </select>
            </td>
        </tr>
    </table>
    
	
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
        <p><?php echo sprintf(__('Insert the following rows into your %s file:',PL_DOMAIN),'<code>functions.php</code>') ?></p>
        <pre style='background-color: #eaeaea'>
            function my_permalinks(){
                register_permanent_link(array(
                    'slug' => 'your_slug',
                    'description' => 'your_description',
                    ));
                }
            add_action('init', 'my_permalinks');
        </pre>
        <p><?php echo sprintf(__('Insert the following function into your %s files:',PL_DOMAIN),'<code>Page Template</code>') ?></p>
        <code>
             wp_permanent_link('slug', 'text', 'class');
        </code>
</div>
