# سكريبت PowerShell لرفع وتشغيل Logstash V2

$SERVER = "root@145.223.98.97"

Write-Host "`n======================================" -ForegroundColor Green
Write-Host "1. رفع ملفات Logstash إلى السيرفر..." -ForegroundColor Cyan
Write-Host "======================================`n" -ForegroundColor Green

# رفع ملف config
Write-Host "رفع logstash_pages_simple_v2.conf..." -ForegroundColor Yellow
scp logstash_pages_simple_v2.conf "${SERVER}:/root/"

# رفع سكريبت التشغيل
Write-Host "رفع start_logstash_v2.sh..." -ForegroundColor Yellow
scp start_logstash_v2.sh "${SERVER}:/root/"

Write-Host "`n======================================" -ForegroundColor Green
Write-Host "2. إعطاء صلاحيات التشغيل..." -ForegroundColor Cyan
Write-Host "======================================`n" -ForegroundColor Green

ssh $SERVER "chmod +x /root/start_logstash_v2.sh"

Write-Host "`n======================================" -ForegroundColor Green
Write-Host "3. إيقاف Logstash القديم وتشغيل الجديد..." -ForegroundColor Cyan
Write-Host "======================================`n" -ForegroundColor Green

ssh $SERVER "/root/start_logstash_v2.sh"

Write-Host "`n======================================" -ForegroundColor Green
Write-Host "✅ تم! Logstash V2 يعمل الآن" -ForegroundColor Green
Write-Host "======================================`n" -ForegroundColor Green

Write-Host "لمراقبة التقدم:" -ForegroundColor Yellow
Write-Host "ssh $SERVER 'docker logs -f bms_logstash_v2'" -ForegroundColor White

Write-Host "`nللتحقق من عدد الصفحات المفهرسة:" -ForegroundColor Yellow
Write-Host 'Invoke-RestMethod -Uri "http://145.223.98.97:9201/pages_simple_v2/_count"' -ForegroundColor White
