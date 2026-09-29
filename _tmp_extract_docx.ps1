param(
    [Parameter(Mandatory = $true)][string]$Path,
    [Parameter(Mandatory = $true)][string]$Out
)

Add-Type -AssemblyName System.IO.Compression.FileSystem

if (-not (Test-Path -LiteralPath $Path)) {
    Write-Error "File not found: $Path"
    exit 1
}

$zip = [System.IO.Compression.ZipFile]::OpenRead($Path)
try {
    $entry = $zip.Entries | Where-Object { $_.FullName -eq 'word/document.xml' }
    if (-not $entry) { Write-Error 'word/document.xml not found in docx'; exit 1 }
    $reader = New-Object System.IO.StreamReader($entry.Open())
    $xml = $reader.ReadToEnd()
    $reader.Close()
}
finally {
    $zip.Dispose()
}

$t = $xml
$t = $t -replace '<w:tab[^>]*/>', ' | '
$t = $t -replace '<w:br[^>]*/>', "`n"
$t = $t -replace '</w:tc>', ' || '
$t = $t -replace '</w:tr>', "`n"
$t = $t -replace '</w:p>', "`n"
$t = $t -replace '<[^>]+>', ''
$t = $t -replace '&lt;', '<' -replace '&gt;', '>' -replace '&quot;', '"' -replace '&apos;', "'" -replace '&amp;', '&'

$outLines = New-Object System.Collections.Generic.List[string]
foreach ($line in ($t -split "`n")) {
    $clean = $line.Trim()
    $clean = $clean -replace '(\s*\|\|\s*)+$', ''
    $clean = $clean -replace '^(\s*\|\|\s*)+', ''
    if ($clean -eq '') { continue }
    if ($clean -match '^[|\s]+$') { continue }
    $outLines.Add($clean)
}

$result = ($outLines -join "`n")
Set-Content -LiteralPath $Out -Value $result -Encoding UTF8
Write-Host ("OK -> {0} ({1} lines)" -f $Out, $outLines.Count)
