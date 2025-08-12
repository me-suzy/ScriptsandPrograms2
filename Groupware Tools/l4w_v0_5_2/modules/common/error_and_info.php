<?php
    if ($this->model->error_msg != "") { ?>
        <tr class="line">    
            <td class=error colspan=5><?=$this->model->error_msg?></td>
        </tr>
    <?php 
    } 
    if ($this->model->info_msg != "") { ?>
        <tr class="line">
            <td class=message colspan=5><?=$this->model->info_msg?></td>
        </tr>
<?php } ?>