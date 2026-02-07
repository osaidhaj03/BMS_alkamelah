# Elasticsearch Reindexing Monitor
# Monitors the progress of pages_v3 index creation

$targetDocs = 5328152
$indexName = "pages_v3"
$esUrl = "http://145.223.98.97:9201"

Write-Host "==================================" -ForegroundColor Cyan
Write-Host "  Elasticsearch Indexing Monitor  " -ForegroundColor Cyan
Write-Host "==================================" -ForegroundColor Cyan
Write-Host ""

$startTime = Get-Date
$lastCount = 0

while ($true) {
    try {
        $response = Invoke-RestMethod -Method Get -Uri "$esUrl/$indexName/_count" -ErrorAction Stop
        $currentCount = $response.count
        
        $elapsed = (Get-Date) - $startTime
        $progress = [math]::Round(($currentCount / $targetDocs) * 100, 2)
        
        if ($currentCount -gt $lastCount) {
            $docsPerSec = if ($elapsed.TotalSeconds -gt 0) { 
                [math]::Round($currentCount / $elapsed.TotalSeconds, 0) 
            } else { 0 }
            
            $remaining = $targetDocs - $currentCount
            $eta = if ($docsPerSec -gt 0) {
                $seconds = $remaining / $docsPerSec
                [TimeSpan]::FromSeconds($seconds)
            } else {
                [TimeSpan]::Zero
            }
            
            Clear-Host
            Write-Host "==================================" -ForegroundColor Cyan
            Write-Host "  Elasticsearch Indexing Monitor  " -ForegroundColor Cyan
            Write-Host "==================================" -ForegroundColor Cyan
            Write-Host ""
            Write-Host "Index: " -NoNewline
            Write-Host $indexName -ForegroundColor Yellow
            Write-Host ""
            Write-Host "Documents: " -NoNewline
            Write-Host "$currentCount / $targetDocs" -ForegroundColor Green
            Write-Host ""
            Write-Host "Progress: " -NoNewline
            Write-Host "$progress%" -ForegroundColor $(if ($progress -gt 50) { 'Green' } else { 'Yellow' })
            Write-Host ""
            Write-Host "Speed: " -NoNewline
            Write-Host "$docsPerSec docs/sec" -ForegroundColor Cyan
            Write-Host ""
            Write-Host "Elapsed: " -NoNewline
            Write-Host ("{0:hh\:mm\:ss}" -f $elapsed) -ForegroundColor Magenta
            Write-Host ""
            Write-Host "ETA: " -NoNewline
            Write-Host ("{0:hh\:mm\:ss}" -f $eta) -ForegroundColor Magenta
            Write-Host ""
            Write-Host "Last Update: $(Get-Date -Format 'HH:mm:ss')" -ForegroundColor Gray
            
            $lastCount = $currentCount
        }
        
        if ($currentCount -ge $targetDocs) {
            Write-Host ""
            Write-Host "================================" -ForegroundColor Green
            Write-Host "  INDEXING COMPLETE!" -ForegroundColor Green
            Write-Host "================================" -ForegroundColor Green
            break
        }
        
    } catch {
        Write-Host "Error: $_" -ForegroundColor Red
    }
    
    Start-Sleep -Seconds 10
}
