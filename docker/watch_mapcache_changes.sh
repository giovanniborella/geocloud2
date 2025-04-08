#!/bin/bash

listcommand="ls /var/www/geocloud2/app/wms/mapcache/*.xml -l $*"

newfilelist=$( $listcommand 2>/dev/null)
while true
do
	if [[ $oldfilelist != $newfilelist ]]
	then
		oldfilelist=$newfilelist
		/usr/bin/node /reload.js
	fi
	sleep 0.1
	newfilelist=$( $listcommand 2>/dev/null)
done

