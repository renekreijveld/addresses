#! /bin/bash

# Script to package Joomla! component and plugins into a package installer zip file
# Written by: René Kreijveld

baseDir=$(pwd)
srcDir=${baseDir}/Src
packageDir=${baseDir}/packages
name=addresses
packageName=pkg_${name}

exclude=".git .gitignore \*update*.xml"

version=$(grep '<version>' ${baseDir}/package.xml | sed -r 's#.*<version>([^<]+)</version>.*#\1#')
versionDir="${packageDir}/${version}"

packageFile=${packageName}-${version}.zip

echo "\nPackaging version ${version} of ${name} into:\n${versionDir}/${packageFile}.\n"

mkdir -p "${versionDir}"

[ -f ${versionDir}/${packageFile} ] && rm ${versionDir}/${packageFile}

# Component
echo "Creating component installer zip file:\n"
extensionName=${name}
componentFile=${versionDir}/com_${extensionName}.zip

cd ${srcDir}
# Create component zipfile
zip -q -r ${componentFile} \
  administrator/components/com_${extensionName} \
  api/components/com_${extensionName} \
  bruno/Addresses \
  components/com_${extensionName} \
  --exclude ${exclude}
echo "Created ${componentFile}."
cd administrator/components/com_${extensionName}
# Add XML file to component zip
zip -q ${componentFile} ${extensionName}.xml
echo "Added ${extensionName}.xml to com_${extensionName}.zip.\n"

# Plugins
echo "Creating plugin(s) installer zip file(s):\n"
extensionName=addresses

for group in webservices
do
  extensionDir=${srcDir}/plugins/${group}/${extensionName}
  pluginFile=${versionDir}/plg_${group}_${extensionName}.zip

  cd ${extensionDir}
  zip -q -r ${pluginFile} * --exclude ${exclude}
  echo "Created ${pluginFile}.\n"
done

# Package
cd ${versionDir}

cp ${baseDir}/package.xml ${versionDir}/${packageName}.xml
cp ${baseDir}/package_script.php ${versionDir}

echo "Moving zips into installer package zip."
zip -m -q -r ${packageName}-${version}.zip ${packageName}.xml package_script.php *.zip --exclude ${exclude}
echo "\nPackage ready:"
echo "${versionDir}/${packageName}-${version}.zip.\n"