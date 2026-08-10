#!/bin/bash

set -e

ssh bowers1 '
cd /var/www/maldoncycleclub
wp eval-file wp-content/plugins/mcc-results/scripts/sample-data.php
'