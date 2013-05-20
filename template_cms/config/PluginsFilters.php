<?php

    // Add default plugins filters    
    if (TEMPLATE_CMS_EVAL_PHP) addFilter('content', 'evalPHP');   

    addFilter('content', 'parseShortcode', 11);
    
    /**
     * Evaluate a string as PHP code
     * @param string $str String with PHP code.
     * @return mixed
     */
    function evalPHP($str) {
        return preg_replace_callback('/\[php\](.*?)\[\/php\]/ms', 'obEval', $str);
    }