<?php
  if( FALSE !== $show_message):
    ?>
        <div id="message" class="updated settings-error notice is-dismissible">
            <p><strong><?php echo $show_message['title'];?></strong></p>
        <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
        </div>
<?php  
    endif;
?>



<div class="wrap">
    <div class="card">
        <h2>Menu Obfuscator</h2>
        <?php
            
                include(__DIR__."/search_form.php");
            
        ?>
    </div>
</div>