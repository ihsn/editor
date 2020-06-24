<?php
/**
 * 
 * Script output type field
 *
 *  options
 * 
 *  - hide_column_headings - hide column headings 
 */

 $hide_column_headings=false;

 if(isset($options['hide_column_headings'])){
     $hide_column_headings=$options['hide_column_headings'];
 }
?>
<?php if (isset($data) && is_array($data) && count($data)>0 ):?>
    <div class="xsl-caption field-caption">
        <?php echo t($name);?>
    </div>
    <div class="field-value">
        <ul>
            <?php foreach($data as $row):?>
                <?php 
                    $link_text=array($row['title']);
                    if (!empty($row['doi'])){
                        $link_text[]=' (DOI '.$row['doi'].')';
                    }
                ?>
                <li class="mb-2">
                    <?php if(isset($row['type'])):?>
                        <span><?php echo $row['type'];?></span> - 
                    <?php endif;?>
                    <?php if(isset($row['uri'])):?>
                        <a href="<?php echo $row['uri'];?>" target="_blank">
                            <?php echo implode(", ", $link_text);?>
                        </a>
                    <?php else:?>
                        <?php echo implode(", ", $link_text);?>
                    <?php endif;?>
                    <?php if(isset($row['description'])):?>
                        <div><?php echo nl2br($row['description']);?></div>
                    <?php endif;?>
                </li>
            <?php endforeach;?>
        </ul>
    </div>
<?php endif;?>
