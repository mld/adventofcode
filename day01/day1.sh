#!/bin/bash

function printPos {
	X=$1
	Y=$2
	stepsaway=$((${X/#-/} + ${Y/#-/}))
	pre=$3
	post=$4
	echo "$pre[$X|$Y] ($stepsaway)$post"
}

posX=0
posY=0
direction='N'
declare -A map

for NEXT in $(cat | sed -e 's/,//g'); do 
	found=""
	steps=$(echo $NEXT | sed -e 's/[RL]//')
	case "$NEXT" in 
		L*) 
			turn="left"
			case $direction in
				0) direction=3;;
				1) direction=0;;
				2) direction=1;;
				*) direction=2;;
			esac
			;;
		*) 
			turn="right"
			case $direction in
				0) direction=1;;
				1) direction=2;;
				2) direction=3;;
				*) direction=0;;
			esac
			;;
	
	esac

	case $direction in
		0) 
			posY2=$(($posY + $steps))
			for ((N=$(($posY+1));N<=$posY2;N++)); do
				printPos $posX $N "> " ""
				[[ -v map["$posX:$N"] ]] && printPos $posX $N ">>> " ""
				map["$posX:$N"]=1
			done
			posY=$posY2
			;;
		1)
			posX2=$(($posX + $steps))
			for ((N=$(($posX+1));N<=$posX2;N++)); do
				printPos $N $posY "> " ""
				[[ -v map["$N:$posY"] ]] && printPos $N $posY ">>> " ""
				map["$N:$posY"]=1
			done
			posX=$posX2
			;;
		2)
			posY2=$(($posY - $steps))
			for ((N=$(($posY-1));N>=$posY2;N--)); do
				printPos $posX $N "> " ""
				[[ -v map["$posX:$N"] ]] && printPos $posX $N ">>> " ""
				map["$posX:$N"]=1
			done
			posY=$posY2
			;;
		*)
			posX2=$(($posX - $steps))
			for ((N=$(($posX-1));N>=$posX2;N--)); do
				printPos $N $posY "> " ""
				[[ -v map["$N:$posY"] ]] && printPos $N $posY ">>> " ""
				map["$N:$posY"]=1
			done
			posX=$posX2
			;;
	esac

	printPos $posX $posY "" $found
	echo
done
