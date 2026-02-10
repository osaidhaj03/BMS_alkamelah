$SERVER = "root@145.223.98.97"

Write-Host "Uploading Logstash files..." -ForegroundColor Cyan
scp logstash_pages_simple_v2.conf "${SERVER}:/root/"
scp start_logstash_v2.sh "${SERVER}:/root/"

Write-Host "Setting permissions..." -ForegroundColor Cyan
ssh $SERVER "chmod +x /root/start_logstash_v2.sh"

Write-Host "Starting Logstash V2..." -ForegroundColor Cyan
ssh $SERVER "/root/start_logstash_v2.sh"

Write-Host "Done! Monitor logs with: ssh $SERVER 'docker logs -f bms_logstash_v2'" -ForegroundColor Green
