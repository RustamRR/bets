#!/bin/sh
# templatemap_gen.sh        генерация карт шаблонов
# templatemap_gen.sh -t     генерация шаблонов с автоматическим удаление старых карт

DIR="$( cd "$( dirname "$0" )" && pwd )"
projectDir=`dirname $DIR`
moduleDirs=`ls $projectDir/module`
csgobetsDirs=`ls $projectDir/module/csgobets`
autoDelMap=true
for i in "$@"
do
case $i in
    -t)
    autoDelMap=true
    delMap=true
    shift
    ;;
    *)

    ;;
esac
done

if [ $autoDelMap == false ]
then
    while true; do
        read -p "Do you wish to delete old template_map file?" yn
        case $yn in
            [Yy]* ) delMap=true; break;;
            [Nn]* ) delMap=false;break;;
            * ) echo "Please answer yes or no.";;
        esac
    done
fi

for dir in $moduleDirs
do
    if [ -d "$projectDir/module/$dir/view" ]; then
        cd "$projectDir/module/$dir"
        echo `pwd`
        sh -c "$projectDir/vendor/bin/templatemap_generator.php"
    else
        if [ $delMap ]; then
            rm -f "$projectDir/module/$dir/template_map.php"
        fi
        echo "$projectDir/module/$dir has been skipped"
    fi
done;

for dir in $csgobetsDirs
do
    if [ -d "$projectDir/module/csgobets/$dir/view" ]; then
        cd "$projectDir/module/csgobets/$dir"
        echo `pwd`
        sh -c "$projectDir/vendor/bin/templatemap_generator.php"
    else
        if [ $delMap ]; then
            rm -f "$projectDir/module/csgobets/$dir/template_map.php"
        fi
        echo "$projectDir/module/csgobets/$dir has been skipped"
    fi
done;


exit
