# Script to package Joomla! component and plugins into a package installer zip file
# Written by: René Kreijveld
# Windows PowerShell version

# To run this script
# Open PowerShell as Administrator
# Run: Set-ExecutionPolicy -ExecutionPolicy Bypass -Scope CurrentUser (if needed)
# Navigate to your project directory
# Run: .\package.ps1

$baseDir = Get-Location
$srcDir = Join-Path $baseDir "Src"
$packageDir = Join-Path $baseDir "packages"
$installerDir = Join-Path $baseDir "installer"
$name = "addresses"
$packageName = "pkg_$name"

# Read version from package.xml
$packageXmlPath = Join-Path $baseDir "package.xml"
$packageXml = [xml](Get-Content $packageXmlPath)
$version = $packageXml.extension.version

$versionDir = Join-Path $packageDir $version
$packageFile = "$packageName-$version.zip"

Write-Host "`nPackaging version $version of $name into: $packageFile.`n"

Write-Host "- Cleaning old versions"
New-Item -ItemType Directory -Force -Path $versionDir | Out-Null
New-Item -ItemType Directory -Force -Path $installerDir | Out-Null

# If an old version of the package exists, remove it
$oldPackagePath = Join-Path $versionDir $packageFile
if (Test-Path $oldPackagePath) {
    Remove-Item $oldPackagePath
}
$oldInstallerPath = Join-Path $installerDir $packageFile
if (Test-Path $oldInstallerPath) {
    Remove-Item $oldInstallerPath
}

# Component
Write-Host "- Creating component installer zip file"
$componentName = $name
$componentFile = Join-Path $versionDir "com_$componentName.zip"

Set-Location $srcDir

# Create component zipfile
$compressionLevel = [System.IO.Compression.CompressionLevel]::Optimal
$includeBaseDirectory = $false

# Create temporary directory for component files
$tempComponentDir = Join-Path ([System.IO.Path]::GetTempPath()) "component_temp"
if (Test-Path $tempComponentDir) {
    Remove-Item $tempComponentDir -Recurse -Force
}
New-Item -ItemType Directory -Path $tempComponentDir | Out-Null

# Copy component files
$componentPaths = @(
    "administrator\components\com_$componentName",
    "api\components\com_$componentName",
    "components\com_$componentName"
)

foreach ($path in $componentPaths) {
    $sourcePath = Join-Path $srcDir $path
    if (Test-Path $sourcePath) {
        $destPath = Join-Path $tempComponentDir $path
        New-Item -ItemType Directory -Force -Path (Split-Path $destPath) | Out-Null
        Copy-Item $sourcePath $destPath -Recurse
    }
}

# Add component XML file
$componentXmlSource = Join-Path $srcDir "administrator\components\com_$componentName\$componentName.xml"
if (Test-Path $componentXmlSource) {
    Copy-Item $componentXmlSource (Join-Path $tempComponentDir "$componentName.xml")
}

# Create zip file
Add-Type -AssemblyName System.IO.Compression.FileSystem
[System.IO.Compression.ZipFile]::CreateFromDirectory($tempComponentDir, $componentFile, $compressionLevel, $includeBaseDirectory)
Write-Host "- Created $componentFile"

# Clean up temp directory
Remove-Item $tempComponentDir -Recurse -Force

# Plugins
Write-Host "- Creating plugin(s) installer zip file(s)"
$pluginName = "addresses"
$groups = @("webservices")

foreach ($group in $groups) {
    $extensionDir = Join-Path $srcDir "plugins\$group\$pluginName"
    $pluginFile = Join-Path $versionDir "plg_${group}_$pluginName.zip"
    
    if (Test-Path $extensionDir) {
        [System.IO.Compression.ZipFile]::CreateFromDirectory($extensionDir, $pluginFile, $compressionLevel, $includeBaseDirectory)
        Write-Host "- Created $pluginFile"
    }
}

# Bruno installer files
Write-Host "- Creating bruno configuration installer zip"
$extensionName = "bruno"
$extensionDir = Join-Path $srcDir $extensionName
$extensionFile = Join-Path $versionDir "$extensionName.zip"

if (Test-Path $extensionDir) {
    [System.IO.Compression.ZipFile]::CreateFromDirectory($extensionDir, $extensionFile, $compressionLevel, $includeBaseDirectory)
    Write-Host "- Created $extensionFile"
}

# Put package together
Set-Location $versionDir

# Copy package files
Copy-Item (Join-Path $baseDir "package.xml") (Join-Path $versionDir "$packageName.xml")
Copy-Item (Join-Path $baseDir "package_script.php") $versionDir

Write-Host "- Moving zips into installer package zip"

# Create final package zip
$finalPackagePath = Join-Path $versionDir "$packageName-$version.zip"
$archive = [System.IO.Compression.ZipFile]::Open($finalPackagePath, [System.IO.Compression.ZipArchiveMode]::Create)

try {
    # Add XML and PHP files
    $xmlFile = Join-Path $versionDir "$packageName.xml"
    $phpFile = Join-Path $versionDir "package_script.php"
    
    # Add XML file
    $xmlEntry = $archive.CreateEntry("$packageName.xml")
    $xmlEntryStream = $xmlEntry.Open()
    $xmlFileStream = [System.IO.File]::OpenRead($xmlFile)
    $xmlFileStream.CopyTo($xmlEntryStream)
    $xmlFileStream.Close()
    $xmlEntryStream.Close()
    
    # Add PHP file
    $phpEntry = $archive.CreateEntry("package_script.php")
    $phpEntryStream = $phpEntry.Open()
    $phpFileStream = [System.IO.File]::OpenRead($phpFile)
    $phpFileStream.CopyTo($phpEntryStream)
    $phpFileStream.Close()
    $phpEntryStream.Close()
    
    # Add all zip files
    Get-ChildItem $versionDir -Filter "*.zip" | Where-Object { $_.Name -ne "$packageName-$version.zip" } | ForEach-Object {
        $zipEntry = $archive.CreateEntry($_.Name)
        $zipEntryStream = $zipEntry.Open()
        $zipFileStream = [System.IO.File]::OpenRead($_.FullName)
        $zipFileStream.CopyTo($zipEntryStream)
        $zipFileStream.Close()
        $zipEntryStream.Close()
    }
}
finally {
    $archive.Dispose()
}

# Copy to installer directory
Copy-Item $finalPackagePath $installerDir

# Clean up individual files
Remove-Item (Join-Path $versionDir "$packageName.xml")
Remove-Item (Join-Path $versionDir "package_script.php")
Get-ChildItem $versionDir -Filter "*.zip" | Where-Object { $_.Name -ne "$packageName-$version.zip" } | Remove-Item

Write-Host "`nPackage and installer ready:"
Write-Host "$versionDir\$packageFile"
Write-Host "$installerDir\$packageFile`n"

Set-Location $baseDir