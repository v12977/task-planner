#!/usr/bin/env bash
# Make sure this script is executable: chmod +x setup_cron.sh

CRON_JOB="0 * * * * php $(pwd)/src/cron.php >/dev/null 2>&1"
# Install if not already present
( crontab -l | grep -v -F "$CRON_JOB" ; echo "$CRON_JOB" ) | crontab -
echo "CRON job registered: runs src/cron.php every hour."
