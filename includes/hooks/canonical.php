<?php
/**
 * A WHMCS hook for generating a correct canonical url meta tag.
 * By Chris Bolt, www.christopherbolt.com
 * Copyright ChristopherBolt.Com Limited
 *
 * Homepage: https://github.com/christopherbolt/whmcs-canonical/
 * 
 * License: https://www.apache.org/licenses/LICENSE-2.0
 * 
 * For queries or support regarding this script contact support@christopherbolt.com
**/

// includes/hooks/canonical.php

add_hook('ClientAreaHeadOutput', 1, function($vars) {
    if (isset($vars['breadcrumb']) && is_array($vars['breadcrumb'])) {
        $last_crumb = $vars['breadcrumb'][array_key_last($vars['breadcrumb'])];
        if (!empty($last_crumb['link'])) {
            $canonical = $last_crumb['link'];

            // Sometimes the URL contains the web root and sometimes it does not, so make this consistent
            $webRoot = $vars['WEB_ROOT'].'/';
            if (substr($canonical, 0, strlen($webRoot)) != $webRoot) $canonical = $webRoot.$canonical;

            // If a product group then url is just cart.php so we need to get full url a different way
            if (!empty($vars['productgroups']) && !empty($vars['gid'])) {
                foreach($vars['productgroups'] as $group) {
                    if ($group['gid'] == $vars['gid']) {
                        $canonical = $group['routePath'];
                    }
                }
            }

            // Todo: if a product then url is just cart.php so we need to get full url a different way

            // If an announcement it might be missing the seo friendly title
            if (!empty($vars['urlfriendlytitle']) && !strstr($canonical, $vars['urlfriendlytitle'])) {
                $canonical .= '/'.$vars['urlfriendlytitle'].'.html';
            }

            // Remove the web root and replace with the full system url
            $canonical = substr($canonical, strlen($webRoot));
            $canonical = htmlspecialchars($vars['systemurl'].$canonical);
            
            global $smarty;
            $smarty->assign('canonical', $canonical);
            return "<link rel=\"canonical\" href=\"$canonical\">";
        }
    }
});
