<?php

/**
 * <Create your comment here>
 *
 * $Revision: $
 * $Id: $
 * $Date:  $
 * Copyright (C) 2012 Isida-Informatika Ltd.
 *
 * @Author: $Author: $
 * @version $Revision: $
 */
function getStatus() {
    global $IP;
    $status = unserialize(file_get_contents("{$IP}/temp/status"));
    return $status;
}
