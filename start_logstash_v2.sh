#!/bin/bash

echo "======================================"
echo "إيقاف Logstash القديم..."
echo "======================================"

# إيقاف جميع containers الـ Logstash القديمة
docker stop $(docker ps -q --filter "name=logstash") 2>/dev/null || echo "لا يوجد Logstash يعمل حالياً"
docker rm $(docker ps -aq --filter "name=logstash") 2>/dev/null || echo "تم التنظيف"

echo ""
echo "======================================"
echo "بدء Logstash الجديد للفهرسة في pages_simple_v2"
echo "======================================"

# تشغيل Logstash container الجديد
docker run -d \
  --name bms_logstash_v2 \
  --network host \
  -v /root/logstash_pages_simple_v2.conf:/usr/share/logstash/pipeline/logstash.conf:ro \
  -v /root/mysql-connector-j-8.0.33.jar:/usr/share/logstash/mysql-connector.jar:ro \
  -e "LS_JAVA_OPTS=-Xmx2g -Xms2g" \
  --restart unless-stopped \
  docker.elastic.co/logstash/logstash:7.17.6

echo ""
echo "======================================"
echo "✅ تم تشغيل Logstash الجديد!"
echo "======================================"
echo ""
echo "لمراقبة التقدم:"
echo "docker logs -f bms_logstash_v2"
echo ""
echo "للتحقق من عدد الصفحات المفهرسة:"
echo "curl http://145.223.98.97:9201/pages_simple_v2/_count"
echo ""
