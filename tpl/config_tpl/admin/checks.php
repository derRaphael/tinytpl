<?php
    /**
     *
     * Check filesystem sanity and permissions
     *
    **/

    $base = $this->base;

    $checks = array(
        "lib" => array("r","!w"),
        "lib/hooks" => array("r","!w"),
        "lib/misc"  => array("r","!w"),
        "tpl" => array("r","!w"),
        "web" => array("r","!w"),
        "cache" => array("r","w"),
    );

    $results = array(
        "OK" => array(),
        "FAIL" => array(),
    );

    if ( is_dir( $base ) && is_readable( $base ) )
    {
        foreach( $checks as $dir => $permissionArray )
        {
            $target = "$base/$dir";

            if ( is_dir( $target ) )
            {
                foreach( $permissionArray as $permission )
                {

                    if ( $permission === "r" && is_readable( $target ) )
                    {
                        $results["OK"][] = "$dir is readable";
                    }
                    else if ( $permission === "r" && ! is_readable( $target ) )
                    {
                        $results["FAIL"][] = "$dir is not readable";
                    }
                    else if ( $permission === "!r" && ! is_readable( $target ) )
                    {
                        $results["OK"][] = "$dir is not readable";
                    }
                    else if ( $permission === "!r" && is_readable( $target ) )
                    {
                        $results["FAIL"][] = "$dir is readable";
                    }
                    else if ( $permission === "w" && is_writable( $target ) )
                    {
                        $results["OK"][] = "$dir is writable";
                    }
                    else if ( $permission === "w" && ! is_writable( $target ) )
                    {
                        $results["FAIL"][] = "$dir is not writable";
                    }
                    else if ( $permission === "!w" && ! is_writable( $target ) )
                    {
                        $results["OK"][] = "$dir is not writable";
                    }
                    else if ( $permission === "!w" && is_writable( $target ) )
                    {
                        $results["FAIL"][] = "$dir is writable";
                    }
                }
            } else {
                $results["FAIL"][] = "$target directory doesn't exist";
            }
        }
    } else {
        // This actually should never happen
        $results["FAIL"][] = "$base directory doesn't exist or is not readable";
    }

    // Now we have our results, lets print these
?>
<h2>Self Checks</h2>
<p>Tests which passed</p>
<ul>
<?php if (count($results["OK"]) != 0): ?>
<?php foreach( $results["OK"] as $result):?>
    <li style="color:#080;"><?=$result?></li>
<?php endforeach;?>
<?php else: ?>
    <li style="color:#800;">None</li>
<?php endif; ?>
</ul>

<p>Tests which failed</p>
<ul>
<?php if (count($results["FAIL"]) != 0): ?>
<?php foreach( $results["FAIL"] as $result):?>
    <li style="color:#800;"><?=$result?></li>
<?php endforeach;?>
<?php else: ?>
    <li style="color:#080;">None</li>
<?php endif; ?>
</ul>
