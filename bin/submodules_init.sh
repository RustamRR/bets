#!/usr/bin/env bash

DIR="$( cd "$( dirname "$0" )" && pwd )"
projectDir=`dirname $DIR`
moduleDirs=`ls $projectDir/module/csgobets`

cd "$projectDir"
sh -c "git submodule update --init"

exit