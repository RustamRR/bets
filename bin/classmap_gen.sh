#!/bin/sh

DIR="$( cd "$( dirname "$0" )" && pwd )"

projectDir=`dirname $DIR`
moduleDirs=`ls $projectDir/module`
csgobetsDirs=`ls $projectDir/module/csgobets`

for dir in $moduleDirs
do
    cd "$projectDir/module/$dir"
    echo `pwd`
    sh -c "$projectDir/vendor/bin/classmap_generator.php"
done;
for dir in $csgobetsDirs
do
    cd "$projectDir/module/csgobets/$dir"
    echo `pwd`
    sh -c "$projectDir/vendor/bin/classmap_generator.php"
done;


exit
