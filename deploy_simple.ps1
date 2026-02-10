$SERVER = "root@145.223.98.97"

Write-Host "رفع ملفات Logstash..." -ForegroundColor Cyan
scp logstash_pages_simple_v2.conf "${SERVER}:/root/"
scp start_logstash_v2.sh "${SERVER}:/root/"

Write-Host "إعطاء صلاحيات..." -ForegroundColor Cyan
ssh $SERVER "chmod +x /root/start_logstash_v2.sh"

Write-Host "تشغيل Logstash V2..." -ForegroundColor Cyan
ssh $SERVER "/root/start_logstash_v2.sh"

Write-Host "تم!" -ForegroundColor Green
