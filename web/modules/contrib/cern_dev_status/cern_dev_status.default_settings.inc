<?php

/**
 * @file
 * Set default values for configuration variables.
 */

define('DEFAULT_ENABLE_NOINDEX', TRUE);
define('DEFAULT_ENABLE_DEVSTYLING', TRUE);
define('DEFAULT_DEVSTYLING_TYPE', 'dev');
define('DEFAULT_ENABLE_RESTRICTIP', TRUE);
define('DEFAULT_IP_ADDDRESS_LIST', '127.0.0.1' . PHP_EOL .
// CERN network IPs.
                                   '10.0.0.0/8' . PHP_EOL .
                                   '100.64.0.0/10' . PHP_EOL .
                                   '128.141.0.0/16' . PHP_EOL .
                                   '128.142.0.0/16' . PHP_EOL .
                                   '137.138.0.0/16' . PHP_EOL .
                                   '172.16.0.0/12' . PHP_EOL .
                                   '172.18.0.0/16' . PHP_EOL .
                                   '185.249.56.0/22' .PHP_EOL .
                                   '188.184.0.0/15' . PHP_EOL .
                                   '192.65.196.0/23' . PHP_EOL .
                                   '192.91.242.0/24' . PHP_EOL .
                                   '192.168.0.0/16' . PHP_EOL .
                                   '194.12.128.0/18' . PHP_EOL .
                                   '2001:1458::' . PHP_EOL .
                                   '2001:1459::' .PHP_EOL .
                                   'FD01:1458::' .PHP_EOL .
                                   'FD01:1459::'
                                   );
define('DEFAULT_ENABLE_OVERRIDE_AUTH_USERS', TRUE);
