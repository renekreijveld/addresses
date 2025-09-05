#!/bin/bash

# Script to package Joomla! component and plugins into a package installer zip file
# Written by: René Kreijveld
# Based on the work of Pieter-Jan de Vries

baseDir=$(pwd)
srcDir=${baseDir}/Src
packageDir=${baseDir}/packages
installerDir=${baseDir}/installer
name=addresses
packageName=pkg_${name}

exclude=".git .gitignore \*update*.xml"

version=$(grep '<version>' ${baseDir}/package.xml | sed -r 's#.*<version>([^<]+)</version>.*#\1#')
versionDir="${packageDir}/${version}"

packageFile=${packageName}-${version}.zip

echo -e "\nPackaging version ${version} of ${name} into: ${packageFile}.\n"

echo -e "- Cleaning old versions"
mkdir -p "${versionDir}"
mkdir -p "${installerDir}"

# If an old version of the package exists, remove it
[ -f ${versionDir}/${packageFile} ] && rm ${versionDir}/${packageFile}
rm -f ${installerDir}/${packageFile}

# Component
echo -e "- Creating component installer zip file"
componentName=${name}
componentFile=${versionDir}/com_${componentName}.zip

cd ${srcDir}

# Create component zipfile
zip -q -r ${componentFile} \
  administrator/components/com_${componentName} \
  api/components/com_${componentName} \
  components/com_${componentName} \
  --exclude ${exclude}
echo -e "- Created ${componentFile}"
cd administrator/components/com_${componentName}
# Add XML file to component zip
zip -q ${componentFile} ${componentName}.xml
echo -e "- Added ${componentName}.xml to com_${componentName}.zip"

# Plugins
echo -e "- Creating plugin(s) installer zip file(s)"
pluginName=addresses

for group in webservices
do
  extensionDir=${srcDir}/plugins/${group}/${pluginName}
  pluginFile=${versionDir}/plg_${group}_${pluginName}.zip

  cd ${extensionDir}
  zip -q -r ${pluginFile} * --exclude ${exclude}
  echo -e "- Created ${pluginFile}"
done

# Bruno installer files
echo -e "- Creating bruno configuration installer zip"
extensionName=bruno
extensionDir=${srcDir}/${extensionName}
extensionFile=${versionDir}/${extensionName}.zip
cd ${extensionDir}
zip -q -r ${extensionFile} * --exclude ${exclude}
echo -e "- Created ${extensionFile}"

# Put package together
cd ${versionDir}

cp ${baseDir}/package.xml ${versionDir}/${packageName}.xml
cp ${baseDir}/package_script.php ${versionDir}

echo -e "- Moving zips into installer package zip"
zip -m -q -r ${packageName}-${version}.zip ${packageName}.xml package_script.php *.zip --exclude ${exclude}
cp ${versionDir}/${packageName}-${version}.zip ${installerDir}
echo -e "\nPackage and installer ready:"
echo -e "${versionDir}/${packageFile}"
echo -e "${installerDir}/${packageFile}\n"