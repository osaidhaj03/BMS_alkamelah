#!/bin/bash

# هذا السكريبت لرفع الملفات وتشغيل Logstash على السيرفر

SERVER="root@145.223.98.97"

echo "======================================"
echo "1. رفع ملف Logstash config إلى السيرفر..."
echo "======================================"

# رفع ملف config
scp logstash_pages_simple_v2.conf $SERVER:/root/

echo ""
echo "======================================"
echo "2. رفع سكريبت التشغيل..."
echo "======================================"

# رفع سكريبت التشغيل
scp start_logstash_v2.sh $SERVER:/root/
ssh $SERVER "chmod +x /root/start_logstash_v2.sh"

echo ""
echo "======================================"
echo "3. تشغيل Logstash الجديد على السيرفر..."
echo "======================================"

# تشغيل السكريبت على السيرفر
ssh $SERVER "/root/start_logstash_v2.sh"

echo ""
echo "======================================"
echo "✅ انتهى! Logstash يعمل الآن"
echo "======================================"
echo ""
echo "لمراقبة التقدم من جهازك:"
echo "ssh $SERVER 'docker logs -f bms_logstash_v2'"
echo ""
