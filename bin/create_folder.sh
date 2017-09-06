#!/bin/sh

DIR="$( cd "$( dirname "$0" )" && pwd )"

projectDir=`dirname $DIR`

cd "$projectDir"

echo `mkdir migrations`

exit
