<?php
/* @var $this Permalinks */


?>
<div class="wrap">
    <div id="icon-link-manager" class="icon32"></div>
    <h2>Permanent Links</h2>
    <form method=”POST” action=”” >
    <table class=”form-table” >
<?php
    $qs=$this->is_permalinked() ? '?' : '&';
    add_thickbox(); 
    $arr=$this->get_templates_with_code();
    foreach ($arr as $slug => $file):
?>
        
      <tr valign=”top” >
          <th scope=”row” >
            <a href="<?php echo get_permalink($this->get_post_by_template($file)).$qs; ?>represent=1&TB_iframe=true&width=970&height=660" class="thickbox button-secondary">
                <?php echo $this->get_description_by_slug($slug) ?>
            </a>
          </th>
          <td>
                <select name=”color”>
                    <?php $this->renderAllPosts(); ?>
                </select>
          </td>
          <td>
              <input id='<?php echo "$slug" ?>' class='pl-button button-primary' type='button' value='<?php _e('Save','PL'); ?>'/>
          </td>
          <td><span class='spinner'></span></td>
      </tr>
        
<?php endforeach; ?>
        
    </table>
    
	

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
<script>
    (function($){
        $('.pl-button').click(function(e){
            var self=$(this);
            var spinner=$(this).parentsUntil('tr').siblings().find('.spinner');
            console.log(spinner);
            spinner.css('display','inline');
            var post_id=$(this).parentsUntil('tr').siblings().find('select').val();
            var data={post:post_id,slug:$(this).attr('id'),action:'save_permanent_link'};
            $.post(ajaxurl,data,function(data){
                spinner.css('display','none');
            });
        });
    })(jQuery);
</script>