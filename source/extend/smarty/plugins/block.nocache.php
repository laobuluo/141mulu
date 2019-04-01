<?php
function smarty_block_nocache($param, $content, $smarty) {
    if (is_null($content)) {
        return;
    }
	
	return $content;
}
?>